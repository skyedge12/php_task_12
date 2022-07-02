<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
]; 


// Функция getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. 
// Возвращает как результат их же, но склеенные через пробел.

function getFullnameFromParts($surname, $name, $patronymic) {
      return $surname .' '. $name .' '. $patronymic;
}

print_r(getFullnameFromParts('Иванов', 'Иван', 'Иванович'));

// Функция getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. 
// Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronymic’.

function getPartsFromFullname($stringFullname) {
    $arrFullname = explode(' ', $stringFullname);
    $arrParts = array('surname' => $arrFullname[0],
                    'name' => $arrFullname[1],
                    'patronymic' => $arrFullname[2]);
    return $arrParts;
    }
print_r(getPartsFromFullname('Иванов Иван Иванович'));

// Функция getShortName принимает как аргумент строку, содержащую ФИО и возвращающую строку, 
// где сокращается фамилия и отбрасывается отчество.

function getShortName($stringFullname1) {
    $arrFullname1 = getPartsFromFullname($stringFullname1);
    $arrShortName = $arrFullname1['name'] . ' ' . mb_substr($arrFullname1['surname'], 0, 1) . '.';
return $arrShortName;
}

print_r(getShortName('Иванов Иван Иванович'));

// Функция для определения пола человека

function getGenderFromName($fullname)
{
	$partsFromFullname = getPartsFromFullname($fullname);
	$name = $partsFromFullname['name'];
	$surname = $partsFromFullname['surname'];
	$patronymic = $partsFromFullname['patronymic'];
	$genderMail = 0;
	$genderFemail = 0;

	if (mb_substr($patronymic, -2, 2) == 'ич') $genderMail++;
	if ((mb_substr($name, -1, 1) == 'й') || (mb_substr($name, -1, 1) == 'н')) $genderMail++;
	if (mb_substr($surname, -1, 1) == 'в') $genderMail++;
	if (mb_substr($patronymic, -3, 3) == 'вна') $genderFemail++;
	if (mb_substr($name, -1, 1) == 'а') $genderFemail++;
	if (mb_substr($surname, -2, 2) == 'ва') $genderFemail++;

	return ($genderMail <=> $genderFemail);
};

// Определение возрастно-полового состава

function getGenderDescription($example_persons_array)
{
	$genderMailperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderMail = getGenderFromName($fullname);
		if ($genderMail > 0) return $genderMail;
	});

	$genderFemailperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderFemail = getGenderFromName($fullname);
		if ($genderFemail < 0) return $genderFemail;
	});

	$genderUnknownperson = array_filter($example_persons_array, function ($example_persons_array) {
		$fullname = $example_persons_array['fullname'];
		$genderUnknown = getGenderFromName($fullname);
		if ($genderUnknown == 0) return $genderUnknown + 1;
	});

	$personCount = count($example_persons_array);
	$personMailCount = count($genderMailperson);
	$personFemailCount = count($genderFemailperson);
	$genderUnknownperson = count($genderUnknownperson);
	$mailPercent = round((($personMailCount / $personCount) * 100), 1);
	$femailPercent = round((($personFemailCount / $personCount) * 100), 1);
	$unknownPercent = round((($genderUnknownperson / $personCount) * 100), 1);

	return <<<HEREDOCTEXT
	Гендерный состав аудитории:
	---------------------------
	Мужики - $mailPercent %
	Женщины - $femailPercent %
	Не удалось определить - $unknownPercent %
	HEREDOCTEXT;
};

print_r(getGenderDescription($example_persons_array));