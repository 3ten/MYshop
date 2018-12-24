<?php
include "db.php";

if (empty($_POST['order_id'])) exit("добавте товар в корзину чтобы оплатить");

$order_id = $_POST['order_id'];
?>

<?php
function GetSum($order_id, $db)
{
    $sum = 0;
    $orderres = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = $order_id", $db);
    while ($orderrow = ibase_fetch_assoc($orderres)) {
        $articul = $orderrow['ARTICUL'];
        $sumres = ibase_query("select PRICE from SHOP_PRODUCTS where ARTICUL = '$articul'", $db);
        $sumrow = ibase_fetch_assoc($sumres);
        $quantity = mb_convert_encoding($orderrow['QUANTITY'], "UTF-8", "windows-1251");
        $price = mb_convert_encoding($sumrow['PRICE'], "UTF-8", "windows-1251");
        $sum += (int)$quantity * (int)$price;
        //$sum++;
    }
    return $sum;
}

?>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/order_payment.css">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>


<div class="container">
    <div class="col-sm-4" align="center">
        <h1>Ваш заказ<br>на сумму <?php echo GetSum($order_id, $db); ?> рублей</h1>
        <form method="POST" action="OrderRedirect.php">
            номер телефона:<br> <input type="text" name="phone" placeholder="введите номер" class="text"
                                       required><br><br>
            город(населенный пункт):<br> <input type="text" name="city" placeholder="город(населенный пункт)"
                                                class="text" required><br><br>
            адрес(улица,д,кв):<br> <input type="text" name="address" placeholder="адрес(улица,д,кв)" class="text"
                                          required><br><br>
            <input type="hidden" name="targets" value="Заказ №<?php echo $order_id; ?>">
            <input type="hidden" name="label" value="<?php echo $order_id; ?>">
            <input type="hidden" name="sum" value="<?php echo GetSum($order_id, $db); ?>" data-type="number">
            <label><input class="custom-radio" type="radio" name="paymentType"
                          value="PC">Яндекс.Деньгами</label><br><br>
            <label><input class="radio" type="radio" name="paymentType" value="AC">Банковской картой</label> <br><br>
            <input type="submit" class="button" value="Оплатить">
        </form>
    </div>

</div>

</html>