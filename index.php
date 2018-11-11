<?php
session_start();
include("db.php");
$res = ibase_query("select * from SHOP_PRODUCTS", $db);
//$row = ibase_fetch_assoc($res);
if (empty($_SESSION['SESSION'])) {
    $_SESSION['SESSION'] = rand(100000, 999999);
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

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body>

<div class="menu">
    <div class="row">
        <a href="order.php"><img class="logo" src="img/basket.png"></a>
        <input type="text" placeholder="Поиск" id="search">
        <a href="admin.php"><img class="logo" src="img/admin.png"></a>
    </div>
</div>

<div class="container">
    <div class="row order" id="main">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {
            $articul = $row['ARTICUL'];
            $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
            $price = mb_convert_encoding($row['PRICE'], "UTF-8", "windows-1251");
            $session = $_SESSION['SESSION'];
            $path = mb_convert_encoding($row['PHOTO_PATH'], "UTF-8", "windows-1251");
            if (!file_exists($path)) {
                $path = "img/default.jpg";
            }
            $orderres = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='$session' and ARTICUL = '$articul'", $db);
            $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
            $OrderRow = ibase_fetch_assoc($orderres);
            //if ($articul != mb_convert_encoding($OrderRow["ARTICUL"], "UTF-8", "windows-1251")) {
            if (empty($OrderRow["ARTICUL"])) {
                // $IsOrderText = "добавить в корзину";
                $IsOrder = "false";
                $OrderClass = "col-sm-4";
            } else {
                // $IsOrderText = "в корзине";
                $IsOrder = "true";
                $OrderClass = "col-sm-4 added";
            }
            ?>

            <div id="<?php echo $articul ?>" class="<?php echo $OrderClass; ?>" data-isorder="<?php echo $IsOrder ?>"
                 data-name="<?php echo $name; ?>">
                <img src="<?php echo $path ?>" class="img-fluid">
                <h3><?php echo $name ?></h3>
                <p>Здесь, может быть, стоит разместить описание товара</p>
                <p><strong><?php echo $price ?> руб.</strong></p>
            </div>
            <?php
        }
        ?>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('.col-sm-4').click(function () {

            var el = document.getElementById(this.id);
            var IsOrder = el.dataset.isorder;
            if (IsOrder === "true") {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=OrderDell&articul=' + this.id,
                    success: function (data) {
                        alert("Удалено");
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=OrderAdd&articul=' + this.id,
                    success: function (data) {
                        alert("Добавлено");
                        location.reload();
                    }
                });
            }
        });
    });

</script>


</html>
