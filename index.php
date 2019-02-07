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
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
</head>

<body>

<div class="menu">
    <div class="row">
        <a href="order.php"><img class="logo" src="img/basket.png"></a>
        <a href="admin.php"><img id="admin_logo" class="logo" src="img/admin.png"></a>
        <input type="text" placeholder="Поиск" id="search">
    </div>
</div>
<div class="product_menu" id="product_menu" style="display: none">
    <img src="img/default.jpg" id="product_menuPhoto" class="img-fluid">
    <h3 id="product_menuName"></h3>
    <div id="product_menuAsrtBox"></div>

    <input type="button" id="1" class="Obtn" value="добавить">


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
                    $IsOrderText = "нажмите чтобы посмотреть описание и добавть в корзину";
                    $IsOrder = "false";
                    $OrderClass = "col-sm-4";
                    $btntext = 'добавить';
                } else {
                    $IsOrderText = "в корзине";
                    $IsOrder = "true";
                    $OrderClass = "col-sm-4 added";
                    $btntext = 'удалить';
                }
                ?>

                <div id="<?php echo $articul ?>" class="<?php echo $OrderClass; ?>"
                     data-isorder="<?php echo $IsOrder ?>"
                     data-name="<?php echo $name; ?>">
                    <div class="imgbox">
                        <img id="<?php echo $articul; ?>img" src="<?php echo $path ?>" class="img-fluid">
                    </div>
                    <h3 id="<?php echo $articul; ?>name"><?php echo $name ?></h3>


                    <p class="isordertext"> <?php echo $IsOrderText; ?></p>
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

                        <p class="form-control description"><?php echo $description; ?></p>
                        <!-- <input type="button" id="<?php echo $articul; ?>btn" class="Obtn"
                               value="<?php echo $btntext; ?>"> -->
                    </div>

                    <p><strong><?php echo $price ?> руб.</strong></p>
                </div>
                <?php
            }
            ?>

            <?php
        }
        ?>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {

        $('.Obtn').click(function (event) {
            let id = document.getElementsByClassName('Obtn').id.replace(/btn/g, '');
            var el = document.getElementById(id);
            var IsOrder = el.dataset.isorder;
            let AsrtCheck = document.getElementsByName(id + 'checkbox');
            if (AsrtCheck[0]) {
                for (let i = 0; i < AsrtCheck.length; i++) {
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
                            data: 'operation=OrderAdd&articul=' + id + '&asrt=' + AsrtCheck[i].dataset.asrt,
                            success: function (data) {
                                alert("Добавлено");
                                location.reload();
                            }
                        });
                    }
                }
            }
            else {
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
                        data: 'operation=OrderAdd&articul=' + id,
                        success: function (data) {
                            alert("Добавлено");
                            location.reload();
                        }
                    });
                }
            }

        });
        let isClicked = false;
        $('.textarea').click(function (event) {
            isClicked = true;
        });
        $('.col-sm-4').click(function (event) {
            let blockID = this.id;
            if (document.getElementById('product_menu').style.display === 'none') {
                document.getElementById('product_menu').style.display = 'inline-block';

                /* формирование меню товара */
                document.getElementById('product_menuPhoto').src = document.getElementById(this.id + 'img').src;
                document.getElementById('product_menuName').innerText = document.getElementById(this.id + 'name').innerText;
                document.getElementsByClassName('Obtn').id = blockID + 'btn';

                if (document.getElementById(blockID).dataset.isorder === "true") {
                    $(".Obtn").val("удалить");
                } else {
                    $(".Obtn").val("добавить");
                }

                let asrtID = document.getElementById('id_' + blockID + 'asrtBox');
                let asrtBoxCh = $(asrtID).children();
                for (let i = 0; i < asrtBoxCh.length; i++) {

                    let astrLabel = document.createElement('label');
                    astrLabel.className = 'asrtLabel';
                    astrLabel.innerText = asrtBoxCh[i].innerText;
                    astrLabel.appendChild(astrEl);
                    document.getElementById('product_menuAsrtBox').appendChild(astrLabel);

                    let astrEl = document.createElement('input');
                    astrEl.type = 'checkbox';
                    astrEl.id = asrtBoxCh[i].innerText;
                    astrEl.className = 'asrtCheckbox';
                    astrEl.dataset.asrt = asrtBoxCh[i].dataset.asrt;
                    astrEl.name = blockID + 'checkbox';

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

            } else {
                let elements = document.getElementsByClassName('asrtLabel');
                let i = 0;
                while (elements.length > 0) {
                    elements[0].remove();
                }
                document.getElementById('product_menu').style.display = 'none';

            }


        });
    })
    ;
</script>
</html>
