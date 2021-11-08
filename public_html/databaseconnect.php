<?php
$servername = "localhost";
$database = "fmiitgroup";
$username = "root";
$password = "root";

// Создаем соединение
$conn = mysqli_connect($servername, $username, $password, $database);

// Проверяем соединение
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//echo "Соединение установленно!";
mysqli_close($conn);
