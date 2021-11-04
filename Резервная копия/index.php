<?php

# Принимаем запрос
$data = json_decode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: '.print_r($data, 1)."\n", FILE_APPEND);

//https://api.telegram.org/bot2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo/setwebhook?url=https://u1510011.trial.reg.site/index.php

# Переменные
$token = '2081869045:AAHOxlDy3WWSqB2G8W8Jtd80cKBtyy2GkUo';

# Обрабатываем команды
$message = $data['message']['text'];

# Формируем массив для отправления в телеграм
$params = [
    'chat_id' => $data['message']['chat']['id'],
    'text'    => $message
];

# Отправляем запрос в телеграм
file_get_contents('https://api.telegram.org/bot'.$token.'/sendMessage?'.http_build_query($params));
?>
Server is ready

