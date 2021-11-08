<?php
$message = "";
ini_set('upload_max_filesize', '50M'); //ограничение в 50 мб
if ($_SERVER['REQUEST_METHOD'] == "POST" ) {
    if ($_FILES['inputfile']['error'] == UPLOAD_ERR_OK && $_FILES['inputfile']['type'] == 'image/jpeg') { //проверка на наличие ошибок
        $destiation_dir = '../library/' . $_FILES['inputfile']['name']; // директория для размещения файла
        if (move_uploaded_file($_FILES['inputfile']['tmp_name'], $destiation_dir)) { //перемещение в желаемую директорию
            $message = 'Файл загружен!';
        } else {
            $message = 'Ошибка.';
        }
    } else {
        switch ($_FILES['inputfile']['error']) {
            case UPLOAD_ERR_FORM_SIZE:
            case UPLOAD_ERR_INI_SIZE:
                $message = 'Фаил не должен привышать 50 мегабайт.';
                brake;
            case UPLOAD_ERR_NO_FILE:
                $message = 'Файл не выбран';
                break;
        }
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Прикладная Информатика</title>
</head>
<body>
<?php
include('../view/nav.php');
?>
<div class="main">
    <div class="container mt-3">
        <h3>Наша библиотека</h3>
        <form method="post" enctype="multipart/form-data">
            <label for="inputfile">Загрузить файл</label>
            <input class="button" type="file" id="inputfile" name="inputfile"></br>
            <input class="btn btn-success" type="submit" value="Загрузить">
            <p ><? echo $message; ?></p>
        </form>
        <table name="book" class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Ссылка</th>
            </tr>
            </thead>
            <tbody>
            <?
            $dir = scandir('../library/');
            $count = 1;
            foreach ($dir as $key => $value)
            {
                if ('.' !== $value && '..' !== $value) {
                    ?>
                    <tr>
                        <th scope="row"><? echo $count++; ?></th>
                        <td><? echo $value; ?></td>
                        <td>
                            <a href="../library/<? echo $value; ?>">скачать</a>
                        </td>
                    </tr>
                    <?
                }
            }
            ?>
            </tbody>
        </table>
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