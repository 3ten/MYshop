<?php
session_start();

if (empty($_SESSION['NAME'])) {
    header("Location: login.php");
}
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

$categoryRes = ibase_query("SELECT * FROM SHOP_CATEGORY_3TEN", $db);
$category = array();
while ($categoryRow = ibase_fetch_assoc($categoryRes)) {
    $category[] = mb_convert_encoding($categoryRow['CATEGORY'], "UTF-8", "windows-1251");
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
<div class="menu">
    <div class="row">
        <a href="index.php"> <img class="logo" src="img/shop.png"> </a>
        <input type="text" placeholder="Поиск" id="search">
        <a href="#" class="test1"><img id="category_add_btn" class="logo" src="img/down_menu_arrow.png"></a>
    </div>
</div>
<div id="category">
    <label class="cadd">
        <input type="text" class="text-input" id="category_add_txt" placeholder="Введите категорию">
        <input type="button" class="category_add" value="добавить">
    </label>
    <label class="cdell">
        <select id="CategoryDllSelect">
            <?php
            for ($i = 0; $i < count($category); $i++) {
                echo "<option selected value='$category[$i]'>" . $category[$i] . "</option>";
            }
            ?>
        </select>
    </label>
    <input type="button" class="CategoryDell" value="удалить">
</div>


<div class="container">
    <div class="row" id="main">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {
            $price = null;
            $description = '';
            $articul = $row['ARTICUL'];
            $result = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
            $shoprow = ibase_fetch_assoc($result);
            $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
            if (!empty($shoprow["ARTICUL"])) {
                $name = mb_convert_encoding($shoprow["NAME"], "UTF-8", "windows-1251");
                $description = mb_convert_encoding($shoprow["DESCRIPTION"], "UTF-8", "windows-1251");
                $price = mb_convert_encoding($shoprow["PRICE"], "UTF-8", "windows-1251");
                $path = mb_convert_encoding($shoprow["PHOTO_PATH"], "UTF-8", "windows-1251");
                $productCategory = mb_convert_encoding($shoprow["CATEGORY"], "UTF-8", "windows-1251");
            } else {
                $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
                $path = "img/default.jpg";
            }
            if (!file_exists($path)) {
                $path = "img/default.jpg";
            }
            ?>
            <!--Главный блок-->
            <div class="col-sm-4" id="id_<? echo $articul ?>">
                <div class="wrapper">
                    <div class="form-group">
                        <input type="file" class="form-control-file" multiple="multiple" accept=" image/*">
                    </div>
                    <div class="ajax-reply"></div>
                </div>
                <img src="<?php echo $path; ?>" id="id_<?php echo $articul; ?>img" class="img-fluid">


                <label for="id_<?php echo $articul; ?>txt"></label><textarea id="id_<?php echo $articul; ?>txt"
                                                                             placeholder="Введите название продукта"
                                                                             class="form-control"><?php echo $name; ?></textarea>
                <div id="<?php echo $articul; ?>editBlock" class="editBlock">
                    <label for="id_<?php echo $articul; ?>description"></label><textarea
                            id="id_<?php echo $articul; ?>description"
                            placeholder="Введите описание продукта"
                            class="form-control"><?php echo $description; ?></textarea>


                    категория: <label>
                        <select id="<?php echo $articul; ?>_select" class=" ">
                            <?php
                            for ($i = 0; $i < count($category); $i++) {
                                if ($category[$i] == $productCategory) {
                                    echo "<option selected value='$category[$i]'>" . $category[$i] . "</option>";
                                } else {
                                    echo "<option value='$category[$i]'>" . $category[$i] . "</option>";
                                }
                            }
                            $productCategory = "";
                            ?>
                        </select>
                    </label>
                    ассортимент: <br>

                    <?php
                    $srtres = ibase_query("select * from CARDASRT where ARTICUL ='$articul' ");
                    while ($srtrow = ibase_fetch_assoc($srtres)) {
                        $namesrt = mb_convert_encoding($srtrow['NAME'], "UTF-8", "windows-1251");
                        $asrt = mb_convert_encoding($srtrow['ASRT'], "UTF-8", "windows-1251");
                        // echo $namesrt;
                        echo "<label><input id='" . $articul . $asrt . "checkbox' class='checkbox' type='checkbox' name='" . $articul . "checkBoxName' data-asrt='" . $asrt . "' value='$namesrt'>$namesrt</label>";
                        echo "<label><input id='" . $articul . $asrt . "asrtQuantity' type='text' name='1' value='' placeholder='количество' style='display: none'></label>";
                    }
                    //$namesrt = null;
                    ?>

                    <br>
                    цена:<input type="text" id="<?php echo $articul; ?>txt" class="priceText inputbox"
                                placeholder="Введите цену"
                                value="<?php echo $price; ?>">
                </div>
                <div class="form-group">
                    <?php
                    if (empty(mb_convert_encoding($shoprow["ARTICUL"], "UTF-8", "windows-1251"))) {
                        $InShop = "false";
                        ?>
                        <input type="button" id="<?php echo $articul; ?>edit" class="edit_button" value="редактировать">

                        <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                               data-price="<?php echo $price; ?>"
                               data-inshop="<?php echo $InShop; ?>" class="button" value="добавить">
                        <?php
                    } else {
                        $InShop = "true";
                        ?>
                        <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                               data-price="<?php echo $price; ?>"
                               data-inshop="<?php echo $InShop; ?>" class="button re" value="Обновить">
                        <input type="button" id="<?php echo $articul; ?>" class="dell" value="удалить">
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Конец главного блока-->
        <?php } ?>
    </div>
</body>
</html>