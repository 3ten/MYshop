<?php
session_start();
include "db.php";

if (empty($_POST['order_id'])) exit("добавте товар в корзину чтобы оплатить");

$order_id = $_POST['order_id'];
?>

<?php
$login = $_SESSION['LOGIN'];
$GetUserData_SQL = ibase_query("select * from SHOP_USERS_3TEN where LOGIN = '$login'", $db);
$GetUserData = ibase_fetch_assoc($GetUserData_SQL);
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
        if ($orderrow['ASRT'] != null) {
            $asrt = $orderrow['ASRT'];
            $asrtQuantitySQL = ibase_query("select * from SHOP_ASRT_3TEN where ASRT = '$asrt' and ARTICUL = '$articul'", $db);
            $asrtQuantity = ibase_fetch_assoc($asrtQuantitySQL);
            $price = $asrtQuantity['ASRT_QUANTITY'] * (int)$price;
        }

        $sum += (int)$quantity * (int)$price;
        //$sum++;
    }
    return $sum;
}

?>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/order_payment.css">


    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>


<div class="container">
    <div class="col-12" align="center">
        <div class="block">
            <h1>Ваш заказ<br>на сумму <?php echo GetSum($order_id, $db); ?> рублей</h1>
            <form method="POST" action="OrderRedirect.php">

                <div  <?php if (!empty($_SESSION['LOGIN'])) echo "hidden"; ?>>ваше имя(ФИО):<br>
                    <input type="text" name="clientName" placeholder="введите ваше ФИО" class="text"
                           value="<?php echo mb_convert_encoding($GetUserData['LOGIN'], "UTF-8", "windows-1251"); ?>"
                           required ><br><br>
                    номер телефона:<br>
                    <input type="text" name="phone" placeholder="введите номер" class="text"
                           value="<?php echo mb_convert_encoding($GetUserData['NUMBER'], "UTF-8", "windows-1251"); ?>"
                           required><br><br>
                    город(населенный пункт):<br>
                    <input type="text" name="city" placeholder="город(населенный пункт)" class="text"
                           value="<?php echo mb_convert_encoding($GetUserData['CITY'], "UTF-8", "windows-1251"); ?>"
                           required><br><br>
                    адрес(улица,д,кв):<br>
                    <input type="text" name="address" placeholder="адрес(улица,д,кв)" class="text"
                           value="<?php echo mb_convert_encoding($GetUserData['ADDRESS'], "UTF-8", "windows-1251"); ?>"
                           required><br><br>
                </div>
                время доставки(день,время):<br>
                <input type="text" name="DT" placeholder="время доставки" class="text" required><br><br>
                комментарий к заказу:<br>
                <textarea name="comment" placeholder="комментарий к заказу" class="text comment"></textarea><br><br>


                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <input type="hidden" name="sum" value="<?php echo GetSum($order_id, $db); ?>" data-type="number">
                <input type="submit" class="button" value="Заказать">
            </form>
            <p>*бесплатная доставка производится только по поселку Новониколаевский</p>
            <p>*оплата при доставке</p>
        </div>
    </div>

</div>

</html>