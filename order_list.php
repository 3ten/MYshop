<?php
session_start();
if (empty($_SESSION['NAME'])) {
    header("Location: login.php");
}
include("db.php");
?>
<!DOCTYPE html>
<html>
<head>
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order.css">

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
</html>
<?php
if (!empty($_POST['ORDER_ID'])) {
    if ($_POST['paymentType'] = 'CP') {


    } else {
        $order_id = $_POST['ORDER_ID'];
        $PaidOrder = ibase_query("update SHOP_PAIDORDER_LIST_3TEN set STATUS = 'D' where ORDER_ID = $order_id", $db);
    }

}
$OrderRes = ibase_query("select * from SHOP_PAIDORDER_LIST_3TEN", $db);


?>
<html>
<body>
<div class="container">

    <?php
    while ($OrderRow = ibase_fetch_assoc($OrderRes)) {
        $status = mb_convert_encoding($OrderRow['STATUS'], "windows-1251", "UTF-8");
        ?>
        <div class="col-sm-4">
            <?php

            if ($OrderRow['STATUS'] == 'D') {
                $orderId = $OrderRow['ORDER_ID'];
                echo "заказ № $orderId оплачен";
            } else if ($status != 'W') {
                ?>
                <p> номер заказа: <?php echo $OrderRow['ORDER_ID']; ?> </p>
                <p> номер клиента: <?php echo $OrderRow['CLIENT_NUMBER']; ?> </p>
                <p> время закза: <?php echo $OrderRow['ORDER_TIME']; ?> </p>
                <p> почта клиента: <?php echo $OrderRow['EMAIL']; ?> </p>
                <p> ключ клиента: <?php echo $OrderRow['ORDER_KEY']; ?> </p>
                <p>   <?php echo $OrderRow['COMMENT']; ?> </p>
                <p> время заказа: <?php echo $OrderRow['DELIVERY_TIME']; ?> </p>
                <p> сумма заказа: <?php echo $OrderRow['ORDER_SUM']; ?> </p>
                <?php
                $orderId = $OrderRow['ORDER_ID'];
                $productsres = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = $orderId", $db);
                echo ' <p>заказ:<br>';
                while ($productsrow = ibase_fetch_assoc($productsres)) {
                    $articul = $productsrow['ARTICUL'];
                    $productsnameres = ibase_query("select * from SHOP_PRODUCTS where ARTICUL = '$articul'",$db);
                    $productsname = ibase_fetch_assoc($productsnameres);

                    echo 'Артикул: ' . $articul.' название: '.$productsname['NAME'].'</p>';
                }
                if ($status == 'A') {
                    ?>
                    <p> Статус: <?php echo $OrderRow['STATUS']; ?>- оплачен - ожидает доставку</p>
                    <form method="post" action="order_list.php">
                        <input type="hidden" name="ORDER_ID" value="<?php echo $OrderRow['ORDER_ID']; ?>">
                        <input type="submit" class="buttoin" value="Потвердить доставку">
                    </form>
                    <?php
                } else if ($status == 'C') {
                    ?>
                    <p> Статус: <?php echo $OrderRow['STATUS']; ?>- ожидает оплату наличными - ожидает доставку</p>
                    <form method="post" action="order_list.php">
                        <input type="hidden" name="ORDER_ID" value="<?php echo $OrderRow['ORDER_ID']; ?>">
                        <input type="hidden" name="operation" value="payment">
                        <input type="hidden" name="paymentType" value="CP">
                        <input type="submit" class="buttoin" value="Потвердить доставку и оплату">
                    </form>
                    <?php
                } else {
                    echo $status;
                }
            }

            ?>
        </div>


        <?php
    }
    ?>
</div>
</body>
</html>
