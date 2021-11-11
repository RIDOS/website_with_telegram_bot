<?php
$servername = "localhost";
$database = "fmiitgroup";
$username = "root";
$password = "root";

// Создаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);
$conn->set_charset('utf8mb4');
// Проверяем соединение
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo "Соединение установленно!";
mysqli_close($conn);
