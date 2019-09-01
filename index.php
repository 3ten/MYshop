<?php
session_start();
include("db.php");
$res = ibase_query("select * from SHOP_CATEGORY_3TEN", $db);
//$row = ibase_fetch_assoc($res);
if (empty($_SESSION['SESSION'])) {
    $_SESSION['SESSION'] = rand(10000, 99999);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--Запрет масштабирования-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>
    <title>Derkor</title>
    <!--Возможность добавления на главный экран-->
    <meta name="mobile-web-app-capable" content="yes">
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>

    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">
    <script defer src="fontawesome/js/brands.js"></script>
    <script defer src="fontawesome/js/solid.js"></script>
    <script defer src="fontawesome/js/fontawesome.js"></script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/search.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>

</head>
<body>
<div class="bg"></div>
<div class="menu">
    <div class="row">
        <a href="order.php"><span class="menu_logo"><i class="fas fa-shopping-basket fa-3x"></i></span></a>
        <a href="home.php"><span class="menu_logo"><i class="fas fa-user-circle fa-3x"></i></span></a>
        <input type="text" placeholder="Поиск" oninput="searchInput(this, '.mainBox')" id="search">
    </div>
</div>

<div class="product_menu" id="product_menu" style="display: none">
    <button class="product_menu_close" value="закрыть">свернуть</button>
    <div id="product_info_menu">
        <div class="photoBack">
            <img src="img/default.jpg" id="product_menuPhoto" class="img-fluid">
        </div>
        <div class="product_text_menu">
            <h3 id="product_menuName"></h3>
            <div id="description_box_menu"></div>
        </div>
        <div id="product_menuAsrtBox"></div>
    </div>
    <input type="button" id="1" class="Obtn" value="добавить в корзину">
</div>

<div class="container">
    <div class="row order" id="main">
        <?php
        while (@$CategoryRow = ibase_fetch_assoc($res)) {
            $MainCategory = $CategoryRow['CATEGORY'];
            $ProductRes = ibase_query("select * from SHOP_PRODUCTS where CATEGORY ='$MainCategory'", $db);

            $checkRes = ibase_query("select * from SHOP_PRODUCTS where CATEGORY ='$MainCategory'", $db);
            $category = mb_convert_encoding($CategoryRow['CATEGORY'], "UTF-8", "windows-1251");

            $checkRow = ibase_fetch_assoc($checkRes);
            ?>

            <?php
            if (!empty($checkRow['ARTICUL'])) {
                echo '<div class="category" id="id_category"><a>' . $category . '</a></div>';
            }
            while (@$row = ibase_fetch_assoc($ProductRes)) {
                $description = '';
                $articul = $row['ARTICUL'];
                $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
                $price = mb_convert_encoding($row['PRICE'], "UTF-8", "windows-1251");
                $category = mb_convert_encoding($row['CATEGORY'], "UTF-8", "windows-1251");
                $description = mb_convert_encoding($row['DESCRIPTION'], "UTF-8", "windows-1251");
                $session = $_SESSION['SESSION'];
                $path = mb_convert_encoding($row['PHOTO_PATH'], "UTF-8", "windows-1251");
                if (!file_exists($path)) {
                    $path = "img/default.jpg";
                }
                $GetAsrtRes = ibase_query("select * from SHOP_ASRT_3TEN WHERE ARTICUL = '$articul'");

                $orderres = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='$session' and ARTICUL = '$articul'", $db);
                $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
                $OrderRow = ibase_fetch_assoc($orderres);
                //if ($articul != mb_convert_encoding($OrderRow["ARTICUL"], "UTF-8", "windows-1251")) {
                if (empty($OrderRow["ARTICUL"])) {
                    $IsOrderText = "не добавлено";
                    $IsOrder = "false";
                    $OrderClass = "";
                    $btntext = 'добавить';
                } else {
                    $IsOrderText = "в корзине";
                    $IsOrder = "true";
                    $OrderClass = " added";
                    $btntext = 'удалить';
                }
                ?>
                <div id="id_<?php echo $articul ?>" class="mainBox col-sm-3 col-xs-12 d-none d-md-block">
                    <div id="<?php echo $articul ?>"
                         class="productBox<?php echo $OrderClass; ?>"
                         data-isorder="<?php echo $IsOrder ?>"
                         data-name="<?php echo $name; ?>">
                        <div class="imgbox">
                            <img id="<?php echo $articul; ?>img" src="<?php echo $path ?>" class="img-fluid mainImg">
                        </div>
                        <h3 id="<?php echo $articul; ?>name" class="text scrollbar"><?php echo $name ?></h3>
                        <p class="isordertext text"> <?php echo $IsOrderText; ?></p>
                        <div id="<?php echo $articul; ?>des" class="des">
                            <div class="<?php echo $articul; ?>asrtBox" id="id_<?php echo $articul; ?>asrtBox">
                                <?php
                                while ($asrtRow = ibase_fetch_assoc($GetAsrtRes)) {
                                    $asrtName = mb_convert_encoding($asrtRow['ASRT_NAME'], "UTF-8", "windows-1251");
                                    $asrt = mb_convert_encoding($asrtRow['ASRT'], "UTF-8", "windows-1251");
                                    echo "<div id='" . $articul . "' data-asrt='" . $asrt . "' class='asrtText'>$asrtName</div>";
                                }
                                ?>
                            </div>
                            <div id="<?php echo $articul; ?>description" class="description"
                                 data-description="<?php echo $description; ?>"></div>
                        </div>
                        <p class="text"><strong><?php echo $price ?> руб.</strong></p>
                    </div>
                </div>


                <div id="id_<?php echo $articul ?>" class="mainMobileBox col-sm-3 col-xs-12 d-md-none d-block">
                    <div id="<?php echo $articul ?>"
                         class="productBox<?php echo $OrderClass; ?>"
                         data-isorder="<?php echo $IsOrder ?>"
                         data-name="<?php echo $name; ?>">
                        <div class="container-fluid p-0 m-0">
                            <div class="row p-0 m-0">
                                <div class="imgbox col-5">
                                    <img id="<?php echo $articul; ?>img" src="<?php echo $path ?>"
                                         class="img-fluid mainImg">
                                </div>
                                <h3 id="<?php echo $articul; ?>name"
                                    class="text scrollbar col-7"><?php echo $name ?></h3>
                            </div>
                            <div class="row">
                                <p class="isordertext text col-6"> <?php echo $IsOrderText; ?></p>
                                <div id="<?php echo $articul; ?>des" class="des">
                                    <div class="<?php echo $articul; ?>asrtBox" id="id_<?php echo $articul; ?>asrtBox">
                                        <?php
                                        while ($asrtRow = ibase_fetch_assoc($GetAsrtRes)) {
                                            $asrtName = mb_convert_encoding($asrtRow['ASRT_NAME'], "UTF-8", "windows-1251");
                                            $asrt = mb_convert_encoding($asrtRow['ASRT'], "UTF-8", "windows-1251");
                                            echo "<div id='" . $articul . "' data-asrt='" . $asrt . "' class='asrtText'>$asrtName</div>";
                                        }
                                        ?>
                                    </div>
                                    <div id="<?php echo $articul; ?>description" class="description"
                                         data-description="<?php echo $description; ?>"></div>
                                </div>
                                <p class="text"><strong><?php echo $price ?> руб.</strong></p>
                            </div>
                        </div>

                    </div>
                </div>


            <?php } ?>
        <?php } ?>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {

        $('.product_menu_close').click(function (event) {
            document.body.style.overflow = 'auto';
            let elements = document.getElementsByClassName('asrtLabel');
            document.getElementById('description_menu').remove();
            let i = 0;
            while (elements.length > 0) {
                elements[0].remove();
            }
            document.getElementById('product_menu').style.display = 'none';
        });

        $('.Obtn').click(function (event) {
            let id = document.getElementsByClassName('Obtn').id.replace(/btn/g, '');
            var el = document.getElementById(id);
            var IsOrder = el.dataset.isorder;
            let AsrtCheck = document.getElementsByName(id + 'checkbox');

            if (IsOrder === "true") {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=OrderDell&articul=' + id,
                    success: function (data) {
                        alert("Удалено");
                        location.reload();
                    }
                });
            } else {
                if (AsrtCheck[0]) {
                    let isChecked = false;
                    for (let i = 0; i < AsrtCheck.length; i++) {

                        for (let i = 0; i < AsrtCheck.length; i++) {
                            if (AsrtCheck[i].checked)
                                isChecked = true;
                        }
                        if (AsrtCheck[i].checked) {
                            $.ajax({
                                type: 'POST',
                                url: 'operations.php',
                                data: 'operation=OrderAdd&articul=' + id + '&asrt=' + AsrtCheck[i].dataset.asrt,
                                success: function (data) {
                                }
                            });
                        }
                    }
                    if (isChecked) {
                        alert("Добавлено");
                        location.reload();
                    } else {
                        alert('выберете вес')
                    }

                } else {
                    if (IsOrder === "true") {
                        $.ajax({
                            type: 'POST',
                            url: 'operations.php',
                            data: 'operation=OrderDell&articul=' + id,
                            success: function (data) {
                                alert("Удалено");
                                location.reload();
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: 'operations.php',
                            data: 'operation=OrderAdd&articul=' + id + '&asrt=null',
                            success: function (data) {
                                alert("Добавлено");
                                location.reload();
                            }
                        });
                    }
                }
            }

        });
        let isClicked = false;
        $('.textarea').click(function (event) {
            isClicked = true;
        });
        $('.productBox').click(function (event) {
            if (document.documentElement.clientWidth < 500) {
                document.body.style.overflow = 'hidden';
            }

            let blockID = this.id;

            let elements = document.getElementsByClassName('asrtLabel');
            if (document.getElementById('description_menu')) {

                document.getElementById('description_menu').remove();
            }
            let i = 0;
            while (elements.length > 0) {
                elements[0].remove();
            }
            if (document.getElementById('product_menu').style.display === 'none') {
                document.getElementById('product_menu').style.display = 'inline-block';
            } else {
                /*    let elements = document.getElementsByClassName('asrtLabel');
                    document.getElementById('description_menu').remove();
                    let i = 0;
                    while (elements.length > 0) {
                        elements[0].remove();
                    }*/
                // document.getElementById('product_menu').style.display = 'none';

            }

            /* формирование меню товара */
            document.getElementById('product_menuPhoto').src = document.getElementById(blockID + 'img').src;
            document.getElementById('product_menuName').innerText = document.getElementById(blockID + 'name').innerText;
            document.getElementsByClassName('Obtn').id = blockID + 'btn';

            if (document.getElementById(blockID).dataset.isorder === "true") {
                $(".Obtn").val("удалить");
            } else {
                $(".Obtn").val("добавить в корзину");
            }


            let descriptionEl = document.createElement('div');
            descriptionEl.id = 'description_menu';
            descriptionEl.innerText = document.getElementById(blockID + 'description').dataset.description;
            document.getElementById('description_box_menu').appendChild(descriptionEl);


            let asrtID = document.getElementById('id_' + blockID + 'asrtBox');
            let asrtBoxCh = $(asrtID).children();

            for (let i = 0; i < asrtBoxCh.length; i++) {

                let astrEl = document.createElement('input');
                astrEl.type = 'checkbox';
                astrEl.id = asrtBoxCh[i].innerText;
                astrEl.className = 'asrtCheckbox';
                astrEl.dataset.asrt = asrtBoxCh[i].dataset.asrt;
                astrEl.name = blockID + 'checkbox';


                let astrLabel = document.createElement('label');
                astrLabel.className = 'asrtLabel';
                astrLabel.setAttribute('for', asrtBoxCh[i].innerText);
                astrLabel.innerText = asrtBoxCh[i].innerText;
                astrLabel.appendChild(astrEl);
                document.getElementById('product_menuAsrtBox').appendChild(astrLabel);

            }
            /* формирование меню товара */

            if (isClicked === false) {
                if (document.getElementById(this.id + 'des').style.display === 'block') {
                    document.getElementById(this.id + 'des').style.display = 'none';

                } else {
                    //       document.getElementById(this.id + 'des').style.display = 'block';
                }
            } else {
                isClicked = false;
            }
        });
    });
</script>
</html>
