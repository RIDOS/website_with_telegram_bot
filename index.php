<?php
//==================================================================================================================== Backend =========================================================================
mb_internal_encoding('UTF-8');

# Принимаем запрос
$data = json_decode(file_get_contents('php://input'), TRUE);

// Лог файлы
file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

//https://api.telegram.org/bot2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo/setwebhook?url=https://u1510011.trial.reg.site/index.php

# Переменные
$buttons = mb_convert_encoding($data['callback_query']['data'], 'UTF-8');
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN','2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo');

# Обрабатываем команды
$message = mb_convert_encoding(($data['text'] ? $data['text'] : $data['data']), 'UTF-8');
$user_name = mb_convert_encoding($data['chat']['username'], 'UTF-8');


# Начало. Проверка пользователя
require_once('./public_html/databaseconnect.php');
$conn = mysqli_connect($servername, $username, $password, $database);
$conn->query("SET NAMES UTF8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET character_set_client = UTF8");
$conn->query("SET character_set_connection = UTF8");
$conn->query("SET character_set_results = UTF8");
$query = "SELECT users.id, group_name.title FROM users INNER JOIN group_name ON users.id_group = group_name.id WHERE username =  '$user_name'";

$gro = $user_name;
$id = 0;

$repiter = false;

$result = $conn->query($query);

if ($result->num_rows > 0)
{
    # Обработка кнопок уведомления
    switch ($buttons) {
        case '-':
            /*$method = 'deleteMessage';
            $send_data = [
                'id' => $data['id']
            ];*/
            $method = 'sendMessage';
            $send_data = [
                'text' => "Ну ок"
            ];

            break;
    }
    # ============================

    while ($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $gro = $row["title"];
    }

    $conn->close();

    switch ($message)
    {
        case '/start':
            $method = 'sendMessage';
            $send_data = [
                'text' => "Нажми /go"
            ];
            break;

        case '/go':
            $method = 'sendMessage';
            $send_data = [
                'text' => 'Доступные функции',
                'reply_markup' => [
                    'resize_keyboard' => true,
                    'keyboard' => [
                        [
                            ['text' => '📊 Ведомость'],
                            ['text' => '🗓 Рассписание'],
                        ],
                        [
                            ['text' => '📘 ДЗ'],
                            ['text' => '📚 Библиотека'],
                        ]
                        /*,
                        [
                            ['text' => '⏰ Уведомления']
                        ]*/
                    ]
                ]
            ];
            break;

/*
        case '⏰ Уведомления':
            $method = 'sendMessage';
            if ($repiter)
            {
                $send_data = [
                    'text' => 'Выключить уведомления?',
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text' => 'Да',
                                    'callback_data' => 'button_no'
                                ],
                                [
                                    'text' => 'Нет',
                                    'callback_data' => '-'
                                ]
                            ]
                        ]
                    ]
                ];
            }
            else
            {
                $send_data = [
                    'text' => 'Включить уведомления?',
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
                                    'text' => 'Да',
                                    'callback_data' => 'button_yes',
                                ],
                                [
                                    'text' => 'Нет',
                                    'callback_data' => '-',
                                ]
                            ]
                        ]
                    ]
                ];
            }
            break;*/

        case '/help':
            $method = 'sendMessage';
            $send_data = [
                'text' =>
                    "*Приветствую!*\nБот работает совместно с [сайтом](https://u1510011.trial.reg.site/).\nДля того, чтобы пользоваться функциями бота, сначала необходимо авторизаваться. Авторизацию можно начать командой /start.\n\nВсего пользователю предоставленно четыре функции:\n1. _Ведомости_\n2. _Рассписание_\n3. _Домашние задания_\n4. _Библиотека_\n\nПодробнее о каждой функцие:\n1. *Ведомость*. В ведомости представленны все ваши достижения. Данные берутся из сайта. (т.е. пользователи сами ведут учет своих достижений)\n2. *Рассписание*. Эта команда выводит текущее расписание. Оно адаптивное под верхние и нижние недели.\n3. *Домашние задания*. Домашнее задание формируется на [сайте](https://u1510011.trial.reg.site/) группы.\n4. *Библиотека*. Файлы загруженные на сайт и автоматически попадают сюда. Это сделано специально, чтобы пользователи не искали на своих устройствах и скачивали на прямую.\n\nЕсли возникают вопросы обращайтесь к [@RIDOS32](https://t.me/RIDOS32)",
                'parse_mode' => 'Markdown'
            ];
            break;

        case '📊 Ведомость':
            $text = '';
            $conn = mysqli_connect($servername, $username, $password, $database);
            $conn->query("SET NAMES UTF8");
            $conn->query("SET CHARACTER SET UTF8");
            $conn->query("SET character_set_client = UTF8");
            $conn->query("SET character_set_connection = UTF8");
            $conn->query("SET character_set_results = UTF8");
            $que = "SELECT discipline.name, SUM(count_v), value FROM `statement` INNER JOIN users ON statement.id = users.id INNER JOIN discipline ON statement.id_discipline = discipline.id WHERE statement.id_user = $id GROUP BY name";
            $result = $conn->query($que);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $text .= "Предмет: *". $row['name'] . "*\nКол-во выходов к доске: _" . $row['SUM(count_v)'] . "_\nОценки по предмету: _" . ($row['value'] == ''? '-': $row['value']) ."_\n\n";
                }
            }
            $conn->close();

            if ($text == '')
                $text = "Ведомость пока что пуста. Для того чтобы заполнить ведомость перейдите на [сайт](https://u1510011.trial.reg.site/).";

            $method = 'sendMessage';
            $send_data = [
                'text' => $text,
                'parse_mode' => 'Markdown'
            ];
            break;

        // Домашнее задание
        case '📘 ДЗ':
            // Формирование ДЗ.
            $home_work = "";

            $conn = mysqli_connect($servername, $username, $password, $database);
            $conn->query("SET NAMES UTF8");
            $conn->query("SET CHARACTER SET UTF8");
            $conn->query("SET character_set_client = UTF8");
            $conn->query("SET character_set_connection = UTF8");
            $conn->query("SET character_set_results = UTF8");
            $query = "SELECT title, discipline.name, text, date FROM home_work INNER JOIN discipline ON `discipline`.id = `home_work`.id_discipline INNER JOIN `group_name` ON `home_work`.id_group = `group_name`.id WHERE group_name.title = '$gro'";

            if ($result = $conn->query($query)) {
                foreach ($result as $row) {
                    $group_name = $row["title"];
                    $discipline_name = $row["name"];
                    $text = $row["text"];
                    $date = $row["date"];

                    //echo $group_name;

                    if ($date > date('Y-m-d')) {
                        $home_work .= "Дошамнее задание по предмету: *$discipline_name*.\n\nЗадание:\n_$text _\n\nВыполнить до $date.\n\n";
                    }
                }
            }
            $conn->close();
            if ($home_work == '')
                $home_work = 'Нет домашнего задания';

            // Отправка ДЗ.
            $method = 'sendMessage';
            $send_data = [
                'text' => $home_work,
                'parse_mode' => 'Markdown'
            ];
            break;

        case '📚 Библиотека':
            $library_arr = "📚 Вся библеотека:\n";
            $i = 0;

            $dir = scandir('./library/');
            $count = count($dir);
            foreach ($dir as $key => $value) {
                if ('.' !== $value && '..' !== $value) {
                    $library_arr .= "📔 $value\n<a href=\"https://u1510011.trial.reg.site/library/$value\">скачать</a>\n\n";
                }
            }

            $method = 'sendMessage';
            $send_data = [
                'text' => $library_arr,
                'parse_mode' => 'html'
            ];
            break;

        case "🗓 Рассписание":
            $method = 'sendMessage';
            if ($gro == '26') {
                if (week())
                    $send_data = [
                        'text' =>
                            "Понедельник:\n9.15-10.35    ИСиТ (каб. 520а)\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   Математ. анализ (каб. 526)\n\nВторник:\n8.45-10.05    Дифф. уравнения (каб. 526)\n10.20-11.40   Матем. анализ (каб. 526)\n12.00-15.00   ФИЗКУЛЬТУРА\n\nСреда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ЭС (каб. 501)\n12.25-13.45   Матем. анализ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)\n\nЧетверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Философия (каб. 531)\n12.25-13.45   -\n14.45-16.05   -\n\n\nБлижайшее расписание:\n" . today_study() . "\n\nСамое новое расписание можно посмотреть здесь:\n",

                        'reply_markup' => [
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => 'открыть',
                                        'url' => "https://bashedu.ru/sites/default/files/upload/465/files/2course_aut2021.pdf"
                                    ]
                                ]
                            ]
                        ]
                    ];
                else
                    $send_data = [
                        'text' =>
                            "Понедельник:\n9.15-10.35    ИСиТ (каб. 520а)\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   Математ. анализ (каб. 526)\n\nВторник:\n8.45-10.05    Дифф. уравнения (каб. 526)\n10.20-11.40   Матем. анализ (каб. 526)\n12.00-15.00   ФИЗКУЛЬТУРА\n\nСреда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ВССТ (каб. 520а)\n12.25-13.45   ВССТ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)\n\nЧетверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Философия (каб. 531)\n12.25-13.45   -\n14.45-16.05   -\n\n\nБлижайшее расписание:\n" . today_study() . "\n\nСамое новое расписание можно посмотреть здесь:\n",
                        'url' => 'https://bashedu.ru/sites/default/files/upload/465/files/2course_aut2021.pdf'
                    ];
            }
            else
            {
                if (week())
                    $send_data = [
                        'text' =>
                            "Понедельник:\n9.15-10.35    -\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   ИСиТ (520а, 521)\n\nВторник:\n8.45-10.05    Матем. анализ (каб. 526)\n10.20-11.40   Дифференциальные уравнения (каб. 515)\n12.00-15.00   ФИЗКУЛЬТУРА\n\nСреда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ВССТ (каб. 520, 521)\n12.25-13.45   Матем. анализ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)\n\nЧетверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Матем. анализ (каб. 526)\n12.25-13.45   Философия (каб. 526)\n14.45-16.05   -\n\n\nБлижайшее расписание:\n" . today_study_2() . "\n\nСамое новое расписание можно посмотреть здесь:\n",

                        'reply_markup' => [
                            'inline_keyboard' => [
                                [
                                    [
                                        'text' => 'открыть',
                                        'url' => "https://bashedu.ru/sites/default/files/upload/465/files/2course_aut2021.pdf"
                                    ]
                                ]
                            ]
                        ]
                    ];
                else
                    $send_data = [
                        'text' =>
                            "Понедельник:\n9.15-10.35    -\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   ИСиТ (520а, 521)\n\nВторник:\n8.45-10.05    Матем. анализ (каб. 526)\n10.20-11.40   Дифференциальные уравнения (каб. 515)\n12.00-15.00   ФИЗКУЛЬТУРА\n\nСреда:\n9.15-10.35    Дифференциальные уравнения (каб. 515)\n10.50-12.10   ЭС (каб. 501)\n12.25-13.45   ВССТ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)\n\nЧетверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Матем. анализ (каб. 526)\n12.25-13.45   Философия (каб. 526)\n14.45-16.05   -\n\n\n
                            Ближайшее расписание:\n" . today_study_2() . "\n\nСамое новое расписание можно посмотреть здесь:\n",
                        'url' => 'https://bashedu.ru/sites/default/files/upload/465/files/2course_aut2021.pdf'
                    ];
            }
            break;

        default:
            $method = 'sendMessage';
            $send_data = [
                'text' => 'Неизвестная команда.'
            ];
    }
}
else
{
    switch ($message)
    {
        case '/start':
            $method = 'sendMessage';
            $send_data = [
                'text' => "Для начала необходимо авторизоваться. Для этого введи свою фамилию:"
            ];
            break;
        case '/help':
            $method = 'sendMessage';
            $send_data = [
                'text' =>
                    "*Приветствую!*\nБот работает совместно с [сайтом](https://u1510011.trial.reg.site/).\nДля того, чтобы пользоваться функциями бота, сначала необходимо авторизаваться. Авторизацию можно начать командой /start.\n\nВсего пользователю предоставленно четыре функции:\n1. _Ведомости_\n2. _Рассписание_\n3. _Домашние задания_\n4. _Библиотека_\n5. _Уведомления_\n\nПодробнее о каждой функцие:\n1. *Ведомость*. В ведомости представленны все ваши достижения. Данные берутся из сайта. (т.е. пользователи сами ведут учет своих достижений)\n2. *Рассписание*. Эта команда выводит текущее расписание. Оно адаптивное под верхние и нижние недели.\n3. *Домашние задания*. Домашнее задание формируется на [сайте](https://u1510011.trial.reg.site/) группы.\n4. *Библиотека*. Файлы загруженные на сайт и автоматически попадают сюда. Это сделано специально, чтобы пользователи не искали на своих устройствах и скачивали на прямую.\n5. *Уведомления*. При нажатии на кнопку включаются напоминания, при повторном - отключается. При включении, перед каждой парой за *15* минут будет отправленно уведомление от бота.\n\nЕсли возникают вопросы обращайтесь к [@RIDOS32](https://t.me/RIDOS32)",
                'parse_mode' => 'Markdown'
            ];
            break;
        default:
            $q = "SELECT users.`id`, group_name.title FROM `users` INNER JOIN group_name ON group_name.id = users.id_group WHERE `FirstName` =  '$message'";
            $resul = $conn->query($q);
            if ($resul->num_rows > 0)
            {
                $id = 0;
                foreach ($resul as $row)
                {
                    $id = $row['id'];
                    $gro = $row['title'];
                }

                $new_query = "UPDATE `users` SET `username`='$user_name' WHERE `id` = '$id'";
                if ($r = $conn->query($new_query))
                {
                    $text = "*Авторизация прошла успешно!*\n Для начала работы нажми /go";
                    $method = 'sendMessage';
                    $send_data = [
                        'text' => $text,
                    ];
                }
                else
                {
                    $method = 'sendMessage';
                    $send_data = [
                        'text' => "Не удалось добавить пользователя.",
                    ];
                }
            }
            else
            {
                $method = 'sendMessage';
                $send_data = [
                    'text' => "Пользователя с такой фамилией не удалось найти :/ ".$message,
                ];
            }
            break;
    }
    $conn->close();
}

# Конец.

# Функция четной/нечетной недели.
function week()
{
    $data = date('W');
    if($data % 2 != 0)
        return true; # Четная неделя.
    else
        echo false; # Нечетная неделя.
}

# Функция вывода всего расписания

# Функция вывода бижайшего расписания
function today_study_2()
{
    if (week()) {
        switch (date("N")) {
            case "2":
                return "Вторник:\n8.45-10.05    Матем. анализ (каб. 526)\n10.20-11.40   Дифференциальные уравнения (каб. 515)\n12.00-15.00   ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n9.15-10.35    Дифференциальные уравнения (каб. 515)\n10.50-12.10   ЭС (каб. 501)\n12.25-13.45   ВССТ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Матем. анализ (каб. 526)\n12.25-13.45   Философия (каб. 526)\n14.45-16.05   -";
                break;
            default:
                return "Понедельник:\n9.15-10.35    -\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   ИСиТ (520а, 521)";
                break;
        }

    }
    else
    {
        switch (date("N")) {
            case "2":
                return "Вторник:\n8.45-10.05    Матем. анализ (каб. 526)\n10.20-11.40   Дифференциальные уравнения (каб. 515)\n12.00-15.00   ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ВССТ (каб. 520, 521)\n12.25-13.45   Матем. анализ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Матем. анализ (каб. 526)\n12.25-13.45   Философия (каб. 526)\n14.45-16.05   -";
                break;
            default:
                return "Понедельник:\n9.15-10.35    -\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   ИСиТ (520а, 521)";
                break;
        }
    }
}

function today_study()
{
    if (week()) {
        switch (date("N")) {
            case "2":
                return "Вторник:\n8.45-10.05    Дифф. уравнения (каб. 526)\n10.20-11.40   Матем. анализ (каб. 526)\n12.00-15.00   ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ВССТ (каб. 520а)\n12.25-13.45   ВССТ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Философия (каб. 531)\n12.25-13.45   -\n14.45-16.05   -";
                break;
            default:
                return "Понедельник:\n9.15-10.35    ИСиТ (каб. 520а)\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   Математ. анализ (каб. 526)";
                break;
        }

    }
    else
    {
        switch (date("N")) {
            case "2":
                return "Вторник:\n8.45-10.05    Дифф. уравнения (каб. 526)\n10.20-11.40   Матем. анализ (каб. 526)\n12.00-15.00   ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n9.15-10.35   Дифференциальные уравнения (каб. 515)\n10.50-12.10   ЭС (каб. 501)\n12.25-13.45   Матем. анализ (каб. 501)\n14.45-16.05   ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n9.15-10.35    Матем. анализ (каб. 501)\n10.50-12.10   Философия (каб. 531)\n12.25-13.45   -\n14.45-16.05   -";
                break;
            default:
                return "Понедельник:\n9.15-10.35    ИСиТ (каб. 520а)\n10.50-12.10   Иностранный язык\n12.25-13.45   ИСиТ (каб. 515)\n14.45-16.05   Математ. анализ (каб. 526)";
                break;
        }
    }
}

# Добовляем данные пользователя
$send_data['chat_id'] = $data['chat']['id'];

$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = [])
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        #CURLOPT_PORT => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot'. TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);
    $result = curl_exec($curl);
    curl_close($curl);

    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}

//==================================================================================================================== Fronend =========================================================================
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!--  Favicon  -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!--  Style & Bootstrap  -->
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Прикладная Информатика</title>
</head>
<body>
    <?php
    include('view/nav.php');
    ?>
    <div class="main">
        <div class="container-xxl mt-3">
                <div class="row align-items-center">
                    <div class="col-7">
                        <h1>Приветствую!</h1>
                        <p>Ссылка на telegram <a style="text-decoration: none" href="https://t.me/Group_26PI_NEWS_bot"><code>@Group_26PI_NEWS_bot</code></a></p>
                    </div>

                    <div class="col-5">
                        <img src="tmp/main.jpg" class="w-100" alt="image">
                    </div>
                </div>
        </div>

        <?php
        include('view/about.php');
        ?>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
    <script src="../js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
</body>
</html>
