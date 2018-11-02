<?php
session_start();
//echo $_SESSION['NAME'];
include("db.php");
$session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");
$res = ibase_query("select ARTICUL from SHOP_ORDER_3TEN where SESSION ='$session'", $db);

?>
<!DOCTYPE html>
<html>
<div class="menu">
    <a href="index.php"> <img src="img/shop.png"> </a>

</div>
<div class="pagename">
    <h3>Корзина</h3>
</div>

<div class="container">
    <div class="productrow">
        <div class="row">
            <?php
            $sum = 0;
            while (@$row = ibase_fetch_assoc($res)) {
                $articul = $row['ARTICUL'];

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
                    <div id="' . $articul . '" class="col-sm-4"><img src="<?php echo $path ?>">
                        <h3><?php echo $name ?></h3>
                        <p><?php echo $price ?> руб. </p></div>

                    <?php
                    $sum += (int)$price;
                }
            }
            ?>
        </div>

    </div>
    <input id="button" class="button" type="submit" value="Оплатить">
</div>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/popup.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<div class="container">
    <div class="row">

        <div id="popupContact">
            <a id="popupContactClose">x</a>
            <h1>Ваш заказ<br>на сумму <?php echo $sum; ?> рублей</h1>
            <p id="contactArea">
            <form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
                <input type="hidden" name="receiver" value="410016725577528">
                <input type="hidden" name="quickpay-form" value="shop">
                <input type="hidden" name="targets" value="Заказ">
                <input type="hidden" name="sum" value="<?php echo $sum; ?>" data-type="number">
                <input type="hidden" name="need-phone" value="+7"><br>
                <label><input type="radio" name="paymentType" value="PC">Яндекс.Деньгами</label><br>
                <label><input type="radio" name="paymentType" value="AC">Банковской картой</label> <br>
                <input type="hidden" name="successURL" value="http://212.17.28.36:88/success.php">
                <input type="submit" value="Оплатить">
            </form>
            </p>
        </div>
        <div id="backgroundPopup"></div>

    </div>
</div>


<body>
</body>
</html>

