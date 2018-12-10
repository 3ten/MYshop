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
    <script src="js/popup.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<div class="menu">
    <div class="row">
        <a href="index.php"> <img class="logo" src="img/shop.png"> </a>
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
                    <a id="Product_Order_dellBtn">x</a>
                    <img src="<?php echo $path; ?>" class="img-fluid">
                    <h3><?php echo $name; ?></h3>
                    <p><?php echo $price; ?> руб.</p>
                    <input type="text"
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


    <input id="button" class="button" type="submit" value="Оплатить">
</div>

<div class="container">
    <div class="row">

        <div id="popupContact">
            <a id="popupContactClose">x</a>
            <h1>Ваш заказ<br>на сумму <?php echo $sum; ?> рублей</h1>
            <p id="contactArea">
            <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
                <input type="hidden" name="receiver" value="410016725577528">
                <input type="hidden" name="quickpay-form" value="shop">
                <input type="hidden" name="targets" value="Заказ №<?php echo $order_id; ?>">
                <input type="hidden" name="label" value="<?php echo $order_id; ?>">
                <input type="hidden" name="sum" value="<?php echo $sum; ?>" data-type="number">
                <input type="hidden" name="need-phone" value="+7"><br>
                <label><input type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label><br>
                <label><input type="radio" name="paymentType" value="AC">Банковской картой</label> <br>
                <input type="hidden" name="successURL" value="http://212.17.28.36:88:33888/success.php">
                <input type="submit" value="Оплатить">
            </form>
            </p>
        </div>
        <div id="backgroundPopup"></div>

    </div>
</div>


</body>
</html>

