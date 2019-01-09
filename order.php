<?php
session_start();
//echo $_SESSION['NAME'];
include("db.php");
$session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");
$res = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='$session'", $db);

?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order.css">
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
</head>
<body>
<div class="menu">
    <div class="row">
        <a href="index.php"> <img class="logo" src="img/shop.png"> </a>
        <a href="order_tracking.php"> <img class="logo" src="img/deliveryicon.png"> </a>
        <input type="text" placeholder="Поиск" id="search">

    </div>
</div>

<div class="pagename">
    <h3>Корзина</h3>
</div>

<div class="container">
    <div class="row" id="main">
        <?php
        $sum = 0;

        while (@$row = ibase_fetch_assoc($res)) {
            $quantity = 1;
            $articul = $row['ARTICUL'];
            $order_id = mb_convert_encoding($row['ORDER_ID'], "UTF-8", "windows-1251");
            $quantity = mb_convert_encoding($row['QUANTITY'], "UTF-8", "windows-1251");

            $ProductQuery = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
            $product = ibase_fetch_assoc($ProductQuery);
            $articul = mb_convert_encoding($product['ARTICUL'], "UTF-8", "windows-1251");
            if (!empty($articul)) {
                $name = mb_convert_encoding($product['NAME'], "UTF-8", "windows-1251");
                $price = mb_convert_encoding($product['PRICE'], "UTF-8", "windows-1251");
                $path = mb_convert_encoding($product['PHOTO_PATH'], "UTF-8", "windows-1251");

                if (!file_exists($path)) {
                    $path = "img/default.jpg";
                }
                ?>
                <div id="<?php echo $articul; ?>" class="col-sm-4" data-name="<?php echo $name; ?>">
                    <a class="Product_Order_dellBtn" id="<?php echo $articul; ?>DellBtn" style="color: white">x</a>
                    <img src="<?php echo $path; ?>" id="<?php echo $articul; ?>orderIMG" class="img-fluid orderIMG">
                    <h3><?php echo $name; ?></h3>
                    <p><?php echo $price; ?> руб.</p>
                    количество: <input type="text"
                                       id="<?php echo $articul; ?>quantity"
                                       class="quantity" placeholder="количество"
                                       value="<?php echo $quantity; ?>"
                                       data-orderid="<?php echo $order_id; ?>"
                    >
                </div>

                <?php
                $sum += (int)$price * (int)$quantity;
            }
        }
        ?>
    </div>
    <form method="post" action="order_payment.php">
        <label>
            <input type="text" name="order_id" value="<?php echo $order_id; ?>" hidden/>
        </label>
        <input id="paymentBtn" class="btn" type="submit" value="Оплатить">

    </form>

</div>
</body>

<script>
    $(document).ready(function () {

        $('.col-sm-4').click(function (event) {
            let id = this.id + "orderIMG";
            if (document.getElementById(id).style.display === 'inline') {
                document.getElementById(id).style.display = 'none';
            } else {
                document.getElementById(id).style.display = 'inline';
            }
        });
    });

</script>
</html>

