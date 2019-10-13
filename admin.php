<?php
session_start();

if ($_SESSION['ROLE'] != '0') {
    header("Location: login.php");
}

include("db.php");
$res = ibase_query("select articul, name from CARDSCLA WHERE classif = '2122'", $db);
//$res = ibase_query("select articul, name from CARDSCLA WHERE CLASSIF > -1", $db);
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

    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">
    <script defer src="fontawesome/js/brands.js"></script>
    <script defer src="fontawesome/js/solid.js"></script>
    <script defer src="fontawesome/js/fontawesome.js"></script>

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/search.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
<div class="bg"></div>
<?php require_once('template/menu.php') ?>


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1"
     role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="container-fluid DataModal">
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

                        <label for="ShowOnlyInShop">Показать только те товары которые добавлены в магазин</label><input
                                type="checkbox"
                                id="ShowOnlyInShop">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close
                </button>
                <button type="button" class="btn btn-primary SaveClientData">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <a class="imgBtn"><span class="menu_logo" data-toggle="modal" data-target="#exampleModalCenter"><i
                            class="fas fa-toolbox fa-3x"></i></span></a>
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

            if (empty(mb_convert_encoding($shoprow["ARTICUL"], "UTF-8", "windows-1251"))) {
                $InShop = "false";
            } else {
                $InShop = "true";
            }

            ?>
            <!--Главный блок-->
            <div class="mainBox col-sm-3 col-xs-12" id="id_<? echo $articul ?>" data-inshop="<?php echo $InShop; ?>">
                <div class="productBox">
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
                        <input type="button" id="<?php echo $articul; ?>edit" class="edit_button" value="редактировать">
                        <?php
                        if ($InShop == 'false') {
                            ?>
                            <input type="button" id="<?php echo $articul; ?>" data-name="<?php echo $name; ?>"
                                   data-price="<?php echo $price; ?>"
                                   data-inshop="<?php echo $InShop; ?>" class="button" value="добавить">
                            <?php
                        } else {
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
            </div>

            <!-- Конец главного блока-->
        <?php } ?>
    </div>
    <script>
        $(document).ready(function () {
            /* добовление товара на сайт*/
            $(".button").click(function () {
                let price = document.getElementById(this.id + "txt").value;
                if (price !== '') {
                    let el = document.getElementById(this.id);
                    let name = document.getElementById("id_" + this.id + "txt").value;
                    let category = document.getElementById(this.id + "_select").value;
                    let description = document.getElementById("id_" + this.id + "description").value;

                    let checks = document.getElementsByName(this.id + 'checkBoxName');
                    if (checks) {
                        for (i = 0; i < checks.length; i++) {
                            let asrtName = checks[i].value;
                            let asrt = checks[i].dataset.asrt;
                            let asrtQuantity = document.getElementById(checks[i].id.replace(/checkbox/g, 'asrtQuantity')).value;
                            if (checks[i].checked) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'operations.php',
                                    data: 'operation=ProductAsrtAdd&articul=' + this.id + '&name=' + asrtName + '&quantity=' + asrtQuantity + '&asrt=' + asrt,
                                    success: function (data) {
                                        console.log(data);
                                    }
                                });
                            }
                        }
                    }
                    $.ajax({
                        type: 'POST',
                        url: 'operations.php',
                        data: 'operation=ProductAdd&articul=' + this.id + '&name=' + name + '&price=' + price + '&path=' + myfile_name + '&category=' + category + '&description=' + description,
                        success: function (data) {
                            alert("Добавлено");
                            // location.reload();
                        }
                    });

                } else {
                    alert("введите цену");
                }
            });
        });
    </script>

</body>
</html>