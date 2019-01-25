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
<div class="product_menu">
    <img src="img/default.jpg" id="product_menuPhoto" class="img-fluid">
    <h3 id="product_menuName"></h3>
    <input type="button" id="" class="orderAddBtn"
           value="test">
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

                    <?php
                    while ($asrtRow = ibase_fetch_assoc($GetAsrtRes)) {
                        $asrtName = mb_convert_encoding($asrtRow['ASRT_NAME'], "UTF-8", "windows-1251");;
                        echo "<div class='asrt'><a class='asrtText'>$asrtName</a></div>";
                    }
                    ?>

                    <p class="isordertext"> <?php echo $IsOrderText; ?></p>
                    <div id="<?php echo $articul; ?>des" class="des">
                        <p class="form-control description"><?php echo $description; ?></p>
                        <input type="button" id="<?php echo $articul; ?>btn" class="Obtn"
                               value="<?php echo $btntext; ?>">
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
            let id = this.id.replace(/btn/g, '');
            var el = document.getElementById(id);
            var IsOrder = el.dataset.isorder;
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
        });
        let isClicked = false;
        $('.textarea').click(function (event) {
            isClicked = true;
        });
        $('.col-sm-4').click(function (event) {

            let menu_PhotoPath = document.getElementById(this.id + 'img').src;
            document.getElementById('product_menuPhoto').src = menu_PhotoPath;
            let menu_name = document.getElementById(this.id + 'name').innerText;
            document.getElementById('product_menuName').innerText = menu_name;

            if (isClicked === false) {
                if (document.getElementById(this.id + 'des').style.display === 'block') {
                    document.getElementById(this.id + 'des').style.display = 'none';
                } else {
                    document.getElementById(this.id + 'des').style.display = 'block';
                }
            } else {
                isClicked = false;
            }
        });
    });
</script>
</html>
