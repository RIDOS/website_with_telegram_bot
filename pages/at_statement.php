<?php
require_once('../public_html/databaseconnect.php');
$conn = mysqli_connect($servername, $username, $password, $database);
$conn->query("SET NAMES UTF8");
$conn->query("SET CHARACTER SET UTF8");
$conn->query("SET character_set_client = UTF8");
$conn->query("SET character_set_connection = UTF8");
$conn->query("SET character_set_results = UTF8");
$errors = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group = $_POST['group_select'];
    $stu = $_POST['student_select'];
    $pre = $_POST['predmet_select'];
    $c = $_POST['count_v'];
    $v = $_POST['value_o'];
    $count = 0;
    $value = "";

    $que_1 = mysqli_query($conn, "SELECT * FROM `statement` WHERE `id_user` = '$stu' AND `id_discipline` = '$pre'");
    //  Проверяем, есть ли записи у пользователя.
    if ($que_1) {
        if ($que_1->num_rows) {
            // Поля есть. Изменяем запись.
            while ($row = $que_1->fetch_assoc()) {
                $count = $row['count_v'];
                $value = $row['value'];
            }
            $count += $c;
            if ($v > 0)    // Если пользователь не указал значение, либо значение оказалось меньше, либо равно 0.
                $value .= $v . ' ';

            // "UPDATE `statement` SET `count_v`='$count',`value`='$value' WHERE `id_user`='$stu' AND `id_discipline`='$pre'";
            $que_2 = mysqli_query($conn, "UPDATE `statement` SET `count_v`='$count',`value`='$value' WHERE `id_user`='$stu' AND `id_discipline`='$pre'");
            if ($que_2) {
                header('Location: /pages/statement.php ');
            } else {
                $error = 'Ошибка обновления запроса';
            }
        } else {
            // Полей нет. Добавляем новую запись.
            $count += $c;
            if ($v > 0)    // Если пользователь не указал значение, либо значение оказалось меньше, либо равно 0.
                $value .= $v . ' ';

            $que_2 = mysqli_query($conn, "INSERT INTO `statement`(`id_user`, `id_discipline`, `count_v`, `value`) VALUES ('$stu', '$pre', '$count', '$value')");
            if ($que_2) {
                header('Location: /pages/statement.php ');
            } else {
                $error = 'Ошибка записи';
            }
        }
    }
}
$conn->close();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Прикладная Информатика</title>

</head>
<body>
<?php
include('../view/nav.php');
?>
<div class="main">
    <div class="container mt-5">
        <form method="post">
            <div class="rov">
                <div class="col">
                    <h4>Ведомость</h4>
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
                <div class="col mt-4">
                    <select id="group" class="btn btn-info btn-outline-dark" name="group_select" onchange="FetchGroup(this.value)" >
                        <option selected>Группа</option>
                        <?
                        $conn = mysqli_connect($servername, $username, $password, $database);
                        $conn->query("SET NAMES UTF8");
                        $conn->query("SET CHARACTER SET UTF8");
                        $conn->query("SET character_set_client = UTF8");
                        $conn->query("SET character_set_connection = UTF8");
                        $conn->query("SET character_set_results = UTF8");
                        $id = 0;
                        $query = "SELECT * FROM `group_name`";
                        if ($result = $conn->query($query)) {
                            foreach ($result as $row) {
                                $id = $row["id"];
                                $title = $row["title"];
                                ?>
                                <option value="<? echo $id; ?>"><? echo $title; ?></option>
                                <?php
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                    <select id="student" class="btn btn-info btn-outline-dark dropdown-toggle" onchange="FetchStudent(this.value)" name="student_select" required>
                        <option selected>Студент</option>
                    </select>
                    <select id="predmet" class="btn btn-info btn-outline-dark dropdown-toggle" onchange="FetchStudent(this.value)" name="predmet_select" required>
                        <?php
                        $conn = mysqli_connect($servername, $username, $password, $database);
                        $conn->query("SET NAMES UTF8");
                        $conn->query("SET CHARACTER SET UTF8");
                        $conn->query("SET character_set_client = UTF8");
                        $conn->query("SET character_set_connection = UTF8");
                        $conn->query("SET character_set_results = UTF8");
                        $id = 0;
                        $query = "SELECT * FROM `discipline` WHERE `name` != \"ВССТ\" AND `name` != \"ИСиТ\"";
                        if ($result = $conn->query($query)) {
                            foreach ($result as $row) {
                                $id = $row["id"];
                                $name = $row["name"];
                                ?>
                                ?>
                                <option value="<? echo $id; ?>"><? echo $name; ?></option>
                                <?
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="col mt-4">
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Выходы к доске</label>
                        <div class="col-sm-10">
                            <select id="count_v" class="btn btn-info btn-outline-dark" name="count_v">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Кол-во баллов</label>
                        <div class="col-sm-10">
                            <select id="value_o" class="btn btn-info btn-outline-dark" name="value_o">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-5">Добавить запись</button>
                    <button type="button" class="btn btn-primary mt-5" onclick="window.location.href = 'statement.php';">Назад</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JS Bootstrap -->
<script type="text/javascript">
    function FetchGroup(id) {
        $('#student').html('Студент');
        $.ajax({
            type:'post',
            url:'../ajax/ajaxdata.php',
            data: {
                id_group: id
            },
            success: function (data) {
                $('#student').html(data);
            }
        })
    }

    function FetchStudent(id) {
        $('#statement').html('');
        $.ajax({
            type:'post',
            url:'../ajax/ajaxdata.php',
            data: {
                id_student: id
            },
            success: function (data) {
                $('#statement').html(data);
            }
        })
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
</body>
</html>