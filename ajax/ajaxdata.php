<?php
require_once('../public_html/databaseconnect.php');
$conn = mysqli_connect($servername, $username, $password, $database);

if ($_POST['id_group']) {
    $query = "SELECT * FROM `users` WHERE `id_group` = ".$_POST['id_group'];
    $result = $conn->query($query);
    if ($result->num_rows > 0)
    {
        echo '<option>Студент</option>';
        while ($row = $result->fetch_assoc())
        {
            echo '<option value="'.$row['id'].'">'.$row['FirstName'].' '.$row['MidleName'].'</option>';
        }
    }
    else
    {
        echo '<option>Нет данных</option>';
    }
}
elseif ($_POST['id_student'])
{
    $query = "SELECT discipline.name, SUM(count_v), value FROM `statement` INNER JOIN users ON statement.id = users.id INNER JOIN discipline ON statement.id_discipline = discipline.id WHERE statement.id_user = ".$_POST['id_student'] . " GROUP BY name";
    $result = $conn->query($query);
    $arr = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $arr[] = $row['value'];
            echo '<tr><th scope="row">' . $row['name'] . '</th>' . '<td>' . $row['SUM(count_v)'] . '</td>' . '<td>' . $row['value'] . "</td></tr>";
        }
    }
    else
    {
        echo '<td></td>';
    }
}