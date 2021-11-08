<?php
require_once('../public_html/databaseconnect.php');
$conn = mysqli_connect($servername, $username, $password, $database);
$errors = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (strtotime($_POST['dateTime']) > strtotime(date("Y-m-d")))
    {
        $group = $_POST['group_select'];
        $disticiline = $_POST['discipline_select'];
        $dateTime = $_POST['dateTime'];
        $text = $_POST['text'];

        $query = "INSERT INTO `home_work`(`id_discipline`, `id_group`, `text`, `date`) VALUES ('$disticiline','$group','$text','$dateTime')";
        $rez = mysqli_query($conn, $query);
        if ($rez > 0)
        {
            header( 'Location: /pages/homework.php');
        }
        else
        {
            $errors = "Ошибка запроса.";
        }

        $conn->close();
    }
    else $errors = "Неверный формат даты.";
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
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!--  Style & Bootstrap  -->
    <link href="../css/style.css" rel="stylesheet" type="text/css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Прикладная Информатика</title>
</head>
<body>
<?php
include('../view/nav.php');
?>

<form  method="post" class="container mt-5">
    <div class="container">
        <div class="col mt-3">
            <h3>Добавление домашнего задания.</h3>
            <?
                if ($errors != "") {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        Запрос не выполнен! Ошибка: <? echo $errors; ?>
                    </div>
                    <?
                }
            ?>
        </div>

        <div class="col mt-3">
            <select class="form-select" aria-label="Default select example" name="group_select">
                <option selected>Выберите группу</option>
                <?
                require_once('../public_html/databaseconnect.php');
                $conn = mysqli_connect($servername, $username, $password, $database);
                $query = "SELECT * FROM `group_name`";
                if ($result = $conn->query($query)) {
                    foreach ($result as $row) {
                        $id = $row["id"];
                        $title = $row["title"];
                        ?>
                        <option value="<? echo $id; ?>"><? echo $title; ?></option>
                        <?
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="col mt-3">
            <select class="form-select" aria-label="Default select example" name="discipline_select">
                <option selected>Выберите предмет</option>
                <?
                require_once('../public_html/databaseconnect.php');
                $conn = mysqli_connect($servername, $username, $password, $database);
                $query = "SELECT * FROM `discipline`";
                if ($result = $conn->query($query)) {
                    foreach ($result as $row) {
                        $id = $row["id"];
                        $name = $row["name"];
                        ?>
                        <option value="<? echo $id; ?>"><? echo $name; ?></option>
                        <?
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="col">
            <section class="ftco-section">
                <div class="container">
                    <div class="row">
                        <h5>Выберите дату:</h5>
                        <div class="col-md-12">
                            <div class="elegant-calencar d-md-flex">
                                <div class="wrap-header d-flex align-items-center img" style="background-image: url(../tmp/bg.jpg);">
                                    <p id="reset">Сегодня</p>
                                    <div id="header" class="p-0">
                                        <div class="head-info">
                                            <div class="head-month"></div>
                                            <div class="head-day"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="calendar-wrap">
                                    <div class="w-100 button-wrap">
                                        <div class="pre-button d-flex align-items-center justify-content-center"><i class="fa fa-chevron-left"></i></div>
                                        <div class="next-button d-flex align-items-center justify-content-center"><i class="fa fa-chevron-right"></i></div>
                                    </div>
                                    <table id="calendar">
                                        <thead>
                                        <tr>
                                            <th>Вос</th>
                                            <th>Пон</th>
                                            <th>Вто</th>
                                            <th>Сре</th>
                                            <th>Чет</th>
                                            <th>Пят</th>
                                            <th>Суб</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="dateTime">
            </section>
        </div>
        <div class="col mt-3">
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Текст домашнего задания</label>
                <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </div>
        <div class="col mt-3 mb-5">
            <input type="submit" class="btn btn-success"/>
            <button type="button" class="btn btn-primary">
                <a href="homework.php" style="text-decoration: none; color: white">
                    Назад
                </a>
            </button>
        </div>
    </div>
</form>
<!-- JS Bootstrap -->
<script src="../js/jquery.min.js"></script>
<script src="../js/popper.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
