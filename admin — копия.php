<?php
session_start();
include("db.php");
$res = ibase_query("select articul, name from CARDSCLA WHERE CLASSIF > -1", $db);
//$row = ibase_fetch_assoc($res);
$i = 0;

$order = array();


$path = 'img/'; // директория для загрузки
$ext = array_pop(explode('.', $_FILES['myfile']['name'])); // расширение
$new_name = time() . '.' . $ext; // новое имя с расширением
$full_path = $path . $new_name; // полный путь с новым именем и расширением

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body>
<div class="menu">
    <a href="index.php"> <img src="img/shop.png"> </a>
</div>

<div class="container">

    <?php
    while (@$row = ibase_fetch_assoc($res)) {

        $articul = $row['ARTICUL'];
        $result = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
        $shoprow = ibase_fetch_assoc($result);
        $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
        $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
        $path = mb_convert_encoding($shoprow["PHOTO_PATH"], "UTF-8", "windows-1251");

        if (!file_exists($path)) {
            $path = "img/default.jpg";
        }


        echo
        ' 
       <div  class="col-sm-4">
        
     <div class="wrapper">
     <div class="form-group">
		<input type="file" class="form-control-file" multiple="multiple" accept=".txt,image/*">
		</div>
				<div class="ajax-reply"></div>
	</div>';

        if (empty(mb_convert_encoding($shoprow["ARTICUL"], "UTF-8", "windows-1251"))) {
            $InShopText = "добавить в магазин";
            $InShop = "false";
            echo
                '              
       <img src="img/default.jpg" class="img-fluid"> 
       <h3>' . $name . ' </h3> 
             <p> ' . $InShopText . ' </p>
       <div class="form-group">
        <input type="text" id ="' . $articul . 'txt" class="form-control"> 
        </div>
        <div class="form-group">
        <input type="button" id="' . $articul . '" data-name="' . $name . '" data-price="' . $price . '" data-inshop="' . $InShop . '" class="button" value="добавить">
        </div> 
       </div> 
                ';

        } else {

            $InShopText = "в магазине";
            $InShop = "true";

            echo
                '          
       <img src="' . $path . '" class="img-fluid"> 
         <h3>' . $name . ' </h3> 
         
         <p> ' . $InShopText . ' </p>
         <div class="form-group">
           <input type="text" id ="' . $articul . 'txt" class="form-control">
         </div>
         <div class="form-group">
           <input  type="button" id="' . $articul . '" data-name="' . $name . '" data-price="' . $price . '" data-inshop="' . $InShop . '" class="button" value="Обновить"> 
           <input  type="button" id="' . $articul . '" class="dell" value="удалить">
          </div>
       </div> 
                ';
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
                    // ОК
                    if (typeof respond.error === 'undefined') {

                    }
                    // error
                    else {
                        console.log('ОШИБКА: ' + respond.error);
                    }
                },
                // функция ошибки ответа сервера
                error: function (jqXHR, status, errorThrown) {
                    console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
                }

            });


        });


    })(jQuery);
    /*******************************************************************************/
    $(document).ready(function () {
        $(".button").click(function () {
            var el = document.getElementById(this.id);
            var name = el.dataset.name;
            var price = document.getElementById(this.id + "txt").value;
            var InShop = el.dataset.inshop;
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


</script>
</html>