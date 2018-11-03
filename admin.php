<?php
session_start();
include("db.php");
$res = ibase_query("select articul, name from CARDSCLA WHERE CLASSIF > -1", $db);
//$row = ibase_fetch_assoc($res);
$order = array();


$path = 'img/'; // директория для загрузки
$ext = array_pop(explode('.', $_FILES['myfile']['name'])); // расширение
$new_name = time() . '.' . $ext; // новое имя с расширением
$full_path = $path . $new_name; // полный путь с новым именем и расширением

$articul_min = 99999;
$articul_max = -2;
if ($_FILES['myfile']['error'] == 0) {
    if (move_uploaded_file($_FILES['myfile']['tmp_name'], $full_path)) {
        // Если файл успешно загружен, то вносим в БД (надеюсь, что вы знаете как)
        // Можно сохранить $full_path (полный путь) или просто имя файла - $new_name
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
<div class="menu">
    <div class="row">
        <a href="index.php"> <img src="img/shop.png"> </a>
        <input type="text" placeholder="Поиск" id="search">
    </div>

</div>

<div class="container">
    <div class="row" id="main">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {

        $articul = $row['ARTICUL'];
        $result = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
        $shoprow = ibase_fetch_assoc($result);
        $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
        $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
        $path = mb_convert_encoding($shoprow["PHOTO_PATH"], "UTF-8", "windows-1251");


        if ($articul_max < (int)$articul) {
            $articul_max = (int)$articul;
        }
        if ($articul_min > (int)$articul) {
            $articul_min = (int)$articul;
        }

        if (!file_exists($path)) {
            $path = "img/default.jpg";
        }

        ?>

        <!--  <div class="col-sm-4" id="<? echo $articul ?>"> -->
        <div class="col-sm-4" id="id_<? echo $articul ?>">
            <div class="wrapper">
                <div class="form-group">
                    <input type="file" class="form-control-file" multiple="multiple" accept=" image/*">
                </div>
                <div class="ajax-reply"></div>
            </div>
            <?php
            if (empty(mb_convert_encoding($shoprow["ARTICUL"], "UTF-8", "windows-1251"))) {
            $InShopText = "добавить в магазин";
            $InShop = "false";
            ?>

            <img src="img/default.jpg" class="img-fluid">
            <h3><?php echo $name; ?>  </h3>
            <p> <?php echo $InShopText; ?></p>
            <div class="form-group">
                <input type="text" id="<?php echo $articul; ?>txt" class="form-control">
            </div>
            <div class="form-group">
                <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                       data-price="<?php echo $price; ?>"
                       data-inshop="<?php echo $InShop; ?>" class="button" value="добавить">
            </div>
        </div>

        <?php
        } else {

        $InShopText = "в магазине";
        $InShop = "true";

        ?>
        <img src="<?php echo $path; ?>" class="img-fluid">
        <h3><?php echo $name; ?></h3>

        <p> <?php echo $InShopText; ?> </p>
        <div class="form-group">
            <input type="text" id="<?php echo $articul; ?>txt" class="form-control">
        </div>
        <div class="form-group">
            <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                   data-price="<?php echo $price; ?>"
                   data-inshop="<?php echo $InShop; ?>" class="button" value="Обновить">
            <input type="button" id="<?php echo $articul; ?>" class="dell" value="удалить">
        </div>
    </div>
    <?php
    }


    }
    ?>

</div>

</body>

<script>

    var files_path;
    var myfile_name;


    (function ($) {
        $('.dell').on('click', function (event) {
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=ProductDell&articul=' + this.id,
                success: function (data) {
                    alert("Удалено");
                    location.reload();
                }
            });
        });
        var files; // переменная. будет содержать данные файлов

// заполняем переменную данными файлов, при изменении значения file поля
        $('input[type=file]').on('change', function () {
            files = this.files;
            myfile_name = this.value;
        });

        $('.button').on('click', function (event) {

            event.stopPropagation(); // остановка всех текущих JS событий
            event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

            // ничего не делаем если files пустой
            if (typeof files == 'undefined') return;

            // создадим данные файлов в подходящем для отправки формате
            var data = new FormData();
            $.each(files, function (key, value) {
                data.append(key, value);
            });

            // добавим переменную идентификатор запроса
            data.append('my_file_upload', 1);

            // AJAX запрос
            $.ajax({
                url: './imgeadd.php',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                // отключаем обработку передаваемых данных, пусть передаются как есть
                processData: false,
                // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
                contentType: false,
                // функция успешного ответа сервера
                success: function (respond, status, jqXHR) {
                    // Ок
                    if (typeof respond.error === 'undefined') {

                    }
                    // error
                    else {
                        console.log('ОШИБКА: ' + respond.error);
                    }
                },
                // функция ошибки ответа сервера
                error: function (jqXHR, status, errorThrown) {
                    console.log('ОШИБКА AJAX Запроса: ' + status, jqXHR);
                }
            });
        });

    })(jQuery);
    /*******************************************************************************/

    $(document).ready(function () {
        //document.documentElement.clientHeight
        //document.getElementById('id_00002').offsetHeight

        let elementSum = ~~(document.documentElement.clientHeight / 500) +2;
        let children = $('#main').children();
        let i;
        if (children.length > elementSum) {
            elementSum = elementSum * 3;
        } else {
            elementSum = children.length;
        }

        for (i = 0; i < elementSum; i++) {
            children.eq(i).css({'display': 'inline'});
        }


        window.onscroll = function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height()) {

                if (children.length > (i + elementSum)) {
                    elementSum += i;
                } else {
                    elementSum = children.length;
                }

                for (i; i < elementSum; i++) {

                    let currentElementId = children.eq(i).attr('id').replace(/id_/g, '');
                    let currentElement = document.getElementById(currentElementId);

                    let name = currentElement.dataset.name;
                    let searchText = document.getElementById('search').value;
                    if (name.toUpperCase().indexOf(searchText.toUpperCase()) === -1) {

                    } else {
                        children.eq(i).css({'display': 'inline'});
                    }

                }
            }
        };

        $(".button").click(function () {
            let el = document.getElementById(this.id);
            let name = el.dataset.name;
            let price = document.getElementById(this.id + "txt").value;
            if (price !== '') {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=ProductAdd&articul=' + this.id + '&name=' + name + '&price=' + price + '&path=' + myfile_name,
                    success: function (data) {
                        alert("Добавлено");
                        location.reload();
                    }
                });
            } else {
                alert("введите цену");
            }
        });
    });


    $('#search').on('input', function () {

        /********************************************/
        let children = $('#main').children();
        let currentElement, currentElementId, name, searchText, counter = 0;
        let elementSum = ~~(document.documentElement.clientHeight / 500) + 2;

        for (let i = 0; i < children.length; i++) {
            currentElementId = children.eq(i).attr('id').replace(/id_/g, '');
            currentElement = document.getElementById(currentElementId);
            if (currentElement) {
                name = currentElement.dataset.name;
                searchText = document.getElementById('search').value;
                if (name.toUpperCase().indexOf(searchText.toUpperCase()) === -1) {
                    children.eq(i).css('display', 'none');
                } else {

                    if (counter <= elementSum * 3) {
                        children.eq(i).css({'display': 'inline'});
                        counter++;
                    }
                }
            }

        }
        /********************************************/
    });


</script>
</html>