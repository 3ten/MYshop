<?php
session_start();
include("db.php");
$res = ibase_query("select articul, name from CARDSCLA WHERE CLASSIF > -1", $db);
//$row = ibase_fetch_assoc($res);


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
    <link rel="stylesheet" href="css/admin.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
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
        if (!empty($shoprow["NAME"])) {
            $name = mb_convert_encoding($shoprow["NAME"], "UTF-8", "windows-1251");
        } else {
            $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
        }
        if (!empty($shoprow["PRICE"])) {
            $price = mb_convert_encoding($shoprow["PRICE"], "UTF-8", "windows-1251");
        }
        $path = mb_convert_encoding($shoprow["PHOTO_PATH"], "UTF-8", "windows-1251");

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
            <div class="form-group">
                <label for="id_<?php echo $articul; ?>txt"></label><textarea id="id_<?php echo $articul; ?>txt"
                                                                             placeholder="Введите название продукта"
                                                                             class="form-control"><?php echo $name; ?></textarea>
                <!-- <input type="text" id="id_<php echo $articul; ?>txt" class="form-control"
                       value="<php echo $name; ?>"> -->
            </div>
            <div class="form-group">
                <input type="text" id="<?php echo $articul; ?>txt" class="priceText" placeholder="Введите цену"
                       value="<?php echo $price; ?>">

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


        <div class="form-group">
            <label for="id_<?php echo $articul; ?>txt"></label><textarea id="id_<?php echo $articul; ?>txt"
                                                                         placeholder="Введите название продукта"
                                                                         class="form-control"><?php echo $name; ?></textarea>
            <!-- <input type="text" id="id_<php echo $articul; ?>txt" class="form-control"
                   value="<php echo $name; ?>"> -->
        </div>
        <div class="form-group">

            <input type="text" id="<?php echo $articul; ?>txt" class="priceText"
                   placeholder="Введите цену" value="<?php echo $price; ?>">
            <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                   data-price="<?php echo $price; ?>"
                   data-inshop="<?php echo $InShop; ?>" class="button" value="Обновить">
            <input type="button" id="<?php echo $articul; ?>" class="dell" value="удалить">


        </div>

    </div>
    <?php
    }
    $price = "";

    }
    ?>

</div>

</body>

</html>