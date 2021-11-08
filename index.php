<?php
mb_internal_encoding('UTF-8');

# Принимаем запрос
$data = json_decode(file_get_contents('php://input'), TRUE);


print($data);


file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

//https://api.telegram.org/bot2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo/setwebhook?url=https://u1510011.trial.reg.site/index.php

# Переменные
$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN','2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo');

# Обрабатываем команды
$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']), 'UTF-8');

# Обработка сообщений
switch ($message)
{
    case '/start':
        $method = 'sendMessage';
        $send_data = [
            'text' => "Пока что функционал бота ограничен, но все еще впереди. Вот какие функции доступны тебе уже сейчас:\nкоманда /menu - вызывает меню бота."
        ];
        break;
    case '/menu':
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Доступные функции',
            'reply_markup' => [
                'resize_keyboard' => true,
                'keyboard' =>[
                    [
                        ['text' => 'Полное рассписание'],
                        ['text' => 'Ближайшие расписание'],
                    ],
                    [
                        ['text' => 'Оставить отзыв'],
                    ]
                ]
            ]
        ];
        break;
    case "полное рассписание":
        $method = 'sendMessage';
        if (week())
            $send_data = [
                'text' =>
                    "Понедельник:\n1. ИСиТ (каб. 520а)\n2. Иностранный язык\n3. ИСиТ (каб. 515)\n4. Математ. анализ (каб. 526)\n\nВторник:\n1. Дифф. уравнения (каб. 526)\n2. Матем. анализ (каб. 526)\n3. ФИЗКУЛЬТУРА\n\nСреда:\n1. Дифф. уравнения (каб. 515)\n2. ЭС (каб. 501)\n3. Матем. анализ (каб. 501)\n4. ЭС (Дистанционно)\n\nЧетверг:\n1. Матем. анализ (каб. 501)\n2. Философия (каб. 531)\n3. -\n4. -\n"
            ];
        else
            $send_data = [
                'text' =>
                    "Понедельник:\n1. ИСиТ (каб. 520а)\n2. Иностранный язык\n3. ИСиТ (каб. 515)\n4. Математ. анализ (каб. 526)\n\nВторник:\n1. Дифф. уравнения (каб. 526)\n2. Матем. анализ (каб. 526)\n3. ФИЗКУЛЬТУРА\n\nСреда:\n1. Дифференциальные уравнения (каб. 515)\n2. ВССТ (каб. 520а)\n3. ВССТ (каб. 501)\n4. ЭС (Дистанционно)\n\nЧетверг:\n1. Матем. анализ (каб. 501)\n2. Философия (каб. 531)\n3. -\n4. -\n"
            ];
        break;
    case "ближайшие расписание":
        $method = 'sendMessage';
        $send_data = [
            'text' => today_study()
        ];
        break;
    case 'оставить отзыв':
        $method = 'sendMessage';
        featback($method, "Здарова");
        $send_data = [
            'text' => 'Сообщение было отправленно.'
        ];
        break;
    default:
        $method = 'sendMessage';
        $send_data = [
            'text' => 'Быканул?'
        ];
}

# Функция четной/нечетной недели.
function week()
{
    $data = date('W');
    if($data % 2 === 0)
        return true; # Четная неделя.
    else
        echo false; # Нечетная неделя.
}

# Функция вывода всего расписания

# Функция вывода бижайшего расписания
function today_study()
{
    if (week()) {
        switch (date("N")) {
            case "2":
                return "Вторник:\n1. Дифф. уравнения (каб. 526)\n2. Матем. анализ (каб. 526)\n3. ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n1. Дифференциальные уравнения (каб. 515)\n2. ВССТ (каб. 520а)\n3. ВССТ (каб. 501)\n4. ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n1. Матем. анализ (каб. 501)\n2. Философия (каб. 531)\n3. -\n4. -";
                break;
            default:
                return "Понедельник:\n1. ИСиТ (каб. 520а)\n2. Иностранный язык\n3. ИСиТ (каб. 515)\n4. Математ. анализ (каб. 526)";
                break;
        }

    }
    else
    {
        switch (date("N")) {
            case "2":
                return "Вторник:\n1. Дифф. уравнения (каб. 526)\n2. Матем. анализ (каб. 526)\n3. ФИЗКУЛЬТУРА";
                break;
            case "3":
                return "Среда:\n1. Дифф. уравнения (каб. 515)\n2. ЭС (каб. 501)\n3. Матем. анализ (каб. 501)\n4. ЭС (Дистанционно)";
                break;
            case "4":
                return "Четверг:\n1. Матем. анализ (каб. 501)\n2. Философия (каб. 531)\n3. -\n4. -";
                break;
            default:
                return "Понедельник:\n1. ИСиТ (каб. 520а)\n2. Иностранный язык\n3. ИСиТ (каб. 515)\n4. Математ. анализ (каб. 526)";
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

function featback($method, $text)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        #CURLOPT_PORT => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.telegram.org/bot'. TOKEN . '/' . $method,
        CURLOPT_POSTFIELDS => array(
            'chat_id' => "545913377",
            'text' => $text,
        ),
        CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
    ]);
    $result = curl_exec($curl);
    curl_close($curl);

    return (json_decode($result, 1) ? json_decode($result, 1) : $result);
}
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
