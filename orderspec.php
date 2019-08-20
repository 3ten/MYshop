<?php
session_start();
if ($_SESSION['ROLE']!= '0') {
    header("Location: login.php");
}
include("db.php");

if (isset($_GET['order'])) {

} else {
    header("Location: order_list.php");
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
    <link rel="stylesheet" href="css/order_list.css">

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<div class="bg"></div>
<div class="container">
    <?php
    $order_id = $_GET['order'];
    $orderInfoSQL = ibase_query("select * from  SHOP_PAIDORDER_LIST_3TEN where order_id = $order_id", $db);
    ?>
    <div class="row">
        <div class="col-12">

            <?php
            while ($orderInfo = ibase_fetch_assoc($orderInfoSQL)) {
            $clientName = mb_convert_encoding($orderInfo['CLIENT_NAME'], "UTF-8", "windows-1251");
            $clientNumber = mb_convert_encoding($orderInfo['CLIENT_NUMBER'], "UTF-8", "windows-1251");
            $clientAdds = mb_convert_encoding($orderInfo['CLIENT_ADDRESS'], "UTF-8", "windows-1251");
            $orderSum = mb_convert_encoding($orderInfo['ORDER_SUM'], "UTF-8", "windows-1251");
            $orderStatus = mb_convert_encoding($orderInfo['STATUS'], "UTF-8", "windows-1251");
            $orderTime = mb_convert_encoding($orderInfo['ORDER_TIME'], "UTF-8", "windows-1251");
            $orderComment = mb_convert_encoding($orderInfo['COMMENT'], "UTF-8", "windows-1251");
            ?>
            <div class="col-12 clientInfo">
                <div class="row">
                    <div class="col-5 clientNameHead">
                        <strong>Имя клиента:</strong>
                    </div>
                    <div class="col-7 clientName">
                        <?php echo $clientName; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 clientNumberHead">
                        <strong>номер клиента:</strong>
                    </div>
                    <div class="col-7 clientNumber">
                        <?php echo $clientNumber; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5 clientAddsHead">
                        <strong>адрес клиента:</strong>
                    </div>
                    <div class="col-7 clientAdds">
                        <?php echo $clientAdds; ?>
                    </div>
                </div>
            </div>
            <div class="col-12 infoBox">
                <div class="row">
                    <div class="col-12"><strong>номер заказа: <?php echo $order_id; ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-12"><strong>статус заказа: <?php echo $orderStatus; ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-12"><strong>сумма заказа: <?php echo $orderSum; ?> руб.</strong></div>
                </div>
                <div class="row">
                    <div class="col-12"><strong>время заказа: <?php echo $orderTime; ?></strong></div>
                </div>
                <div class="row">
                    <div class="col-12"><strong>комментарий к заказу:</strong> <br>

                            <textarea readonly class="comment"><?php echo $orderComment; ?></textarea>

                    </div>
                </div>
                <?php
                }
                $getOrderSpecSQL = ibase_query("select * from  SHOP_ORDER_3TEN where order_id = $order_id", $db);
                while ($getOrderSpec = ibase_fetch_assoc($getOrderSpecSQL)) {
                    $asrt = $getOrderSpec['ASRT'];
                    $articul = $getOrderSpec['ARTICUL'];
                    $quantity = $getOrderSpec['QUANTITY'];
                    if ($asrt == '') {
                        $asrt = 'null';
                    }
                    $getProdInfoSQL = ibase_query("select * from  SHOP_GET_PRODUCTINFO_3TEN($asrt,'$articul')", $db);


                    $articul = mb_convert_encoding($articul, "UTF-8", "windows-1251");
                    $asrt = mb_convert_encoding($asrt, "UTF-8", "windows-1251");

                    $getOrderSpec = ibase_fetch_assoc($getProdInfoSQL);
                    $productName = mb_convert_encoding($getOrderSpec['OUT_PROD_NAME'], "UTF-8", "windows-1251");
                    $AsrtName = mb_convert_encoding($getOrderSpec['OUT_ASRT_NAME'], "UTF-8", "windows-1251");
                    $prodSum = mb_convert_encoding($getOrderSpec['OUT_PROD_SUM'], "UTF-8", "windows-1251");
                    $prodSum = (int)$prodSum * (int)$quantity


                    ?>
                    <div class="row prodDesc">
                        <div class="col-5"> <?php echo $productName; ?></div>
                        <!--<div class="col-2"> <?php echo $articul; ?> </div>-->
                        <div class="col-2"> <?php echo $quantity; ?> Шт.</div>

                        <div class="col-3"> <?php echo $AsrtName; ?> </div>
                        <div class="col-2"> <?php echo $prodSum; ?> руб.</div>
                    </div>
                    <?php
                }
                if ($orderStatus != 'D') {
                    ?>
                    <div>
                        <button id="id_acceptBtn" class="acceptBtn" data-orderid="<?php echo $order_id; ?>">подтвердить
                            доставку
                        </button>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>

</div>

</body>

</html>


