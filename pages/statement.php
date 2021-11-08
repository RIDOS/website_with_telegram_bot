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
require_once('../public_html/databaseconnect.php');


?>
<div class="main">
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <h4>Ведомость группы</h4>
                <button type="button" class="btn btn-success" onclick="window.location.href = 'at_statement.php';">Добавить запись</button>
            </div>
            <div class="col mt-4 mb-3" style="flex: none;">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <select id="group" class="btn btn-info btn-outline-dark" name="group_select" onchange="FetchGroup(this.value)" >
                        <option selected>Группа</option>
                        <?
                        $conn = mysqli_connect($servername, $username, $password, $database);
                        $id = 0;
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
                    <select id="student" class="btn btn-info btn-outline-dark dropdown-toggle" onchange="FetchStudent(this.value)" name="group_select" required>
                        <option selected>Студент</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col">
            <h5>Текущая успеваемость</h5>
            <table class="table" >
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Предмет</th>
                    <th scope="col">Кол-во выходов к доске</th>
                    <th scope="col">Оценки</th>
                </tr>
                </thead>
                <tbody id="statement">
                </tbody>
            </table>
        </div>
    </div>
    <div class="container">

        <?
//        $conn = mysqli_connect($servername, $username, $password, $database);
//        $query = "SELECT discipline.name, value FROM `statement` INNER JOIN users ON statement.id = users.id INNER JOIN discipline ON statement.id_discipline = discipline.id WHERE statement.id_user = 1";
//        $result = $conn->query($query);
//        if ($result->num_rows > 0) {
//            $dis = array();
//            $co = array();
//
//            $count = 0;
//            $index = 0;
//
//
//            echo $count;
//        }
        ?>
    </div>
</div>

<!--JS-->
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
        $('#statement').html('Студент');
        $('#statement_2').html('');
        $.ajax({
            type:'post',
            url:'../ajax/ajaxdata.php',
            data: {
                id_student: id
            },
            success: function (data) {
                $('#statement').html(data);
                $('#statement_2').html(data);
            }
        })
    }
</script>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<!--/JS-->

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
<script src="../js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
</body>
</html>