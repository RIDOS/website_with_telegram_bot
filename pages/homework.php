<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <!--  Favicon  -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!--  Style & Bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Прикладная Информатика</title>
</head>
<body>
<?php
include('../view/nav.php');
?>
<div class="main">
    <div class="container">
        <div class="container mt-5">
            <button type="button" class="btn btn-success" onclick="window.location.href = 'at_work.php'">
                    Добавить расписание
            </button>
        </div>
        <div class="row">
            <div class="col">
                <div class="container mt-5">
                    <h4>Домашнее задание для 25 группы:</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Название предмета</th>
                            <th scope="col">Задание</th>
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        require_once('../public_html/databaseconnect.php');

                        $conn = mysqli_connect($servername, $username, $password, $database);
                        $conn->query("SET NAMES UTF8");
                        $conn->query("SET CHARACTER SET UTF8");
                        $conn->query("SET character_set_client = UTF8");
                        $conn->query("SET character_set_connection = UTF8");
                        $conn->query("SET character_set_results = UTF8");
                        $query = "SELECT title, discipline.name, text, date FROM home_work INNER JOIN discipline ON `discipline`.id = `home_work`.id_discipline INNER JOIN `group_name` ON `home_work`.id_group = `group_name`.id";

                        if ($result = $conn->query($query)) {
                            foreach ($result as $row) {
                                $group_name = $row["title"];
                                $discipline_name = $row["name"];
                                $text = $row["text"];
                                $date = $row["date"];

                                //echo $group_name;

                                if ($date > date('Y-m-d')) {
                                    if ($group_name == '25') {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $discipline_name; ?></th>
                                            <td><?php echo $text; ?></td>
                                            <td><?php echo $date; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                        }
                        else echo "Ошибка БД!";
                        mysqli_close($conn);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <div class="container mt-5">
                    <h4>Домашнее задание для 26 группы:</h4>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Название предмета</th>
                            <th scope="col">Задание</th>
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        require_once('../public_html/databaseconnect.php');

                        $conn = mysqli_connect($servername, $username, $password, $database);
                        $conn->query("SET NAMES UTF8");
                        $conn->query("SET CHARACTER SET UTF8");
                        $conn->query("SET character_set_client = UTF8");
                        $conn->query("SET character_set_connection = UTF8");
                        $conn->query("SET character_set_results = UTF8");
                        $query = "SELECT home_work.id, title, discipline.name, text, date FROM home_work INNER JOIN discipline ON `discipline`.id = `home_work`.id_discipline INNER JOIN `group_name` ON `home_work`.id_group = `group_name`.id";
                        if ($result = $conn->query($query)) {
                            foreach ($result as $row) {
                                $id = $row['id'];
                                $group_name = $row["title"];
                                $discipline_name = $row["name"];
                                $text = $row["text"];
                                $date = $row["date"];

                                //echo $group_name;

                                if ($date > date('Y-m-d')) {
                                    if ($group_name == '26') {
                                        ?>
                                        <tr>
                                            <th scope="row"><?php echo $discipline_name; ?></th>
                                            <td><?php echo $text; ?></td>
                                            <td><?php echo $date; ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
                                    $query = "DELETE FROM `home_work` WHERE `home_work`.`id` = ".$id;
                                    $rez = $conn->query($query);
                                    if (!$rez) {
                                        echo "Запрос не выполнен!";
                                    }
                                }
                            }
                        }
                        else echo "Ошибка БД!";
                        mysqli_close($conn);
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  Footer  -->
<footer class="bg-dark">

</footer>
<!-- /Footer   -->

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>