<?php
session_start();
if ($_SESSION['ROLE']!= '0') {
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

    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">
    <script defer src="fontawesome/js/brands.js"></script>
    <script defer src="fontawesome/js/solid.js"></script>
    <script defer src="fontawesome/js/fontawesome.js"></script>

    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!--<link rel="stylesheet" href="css/order.css">-->
    <link rel="stylesheet" href="css/order_list.css">

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>
<body>
<?php require_once('template/menu.php') ?>
<div class="bg"></div>
<div class="container">

    <?php
    $OrderWSD_SQL = ibase_query("select * from SHOP_PAIDORDER_LIST_3TEN where STATUS ='D' order by ORDER_ID", $db);
    $OrderWSP_SQL = ibase_query("select * from SHOP_PAIDORDER_LIST_3TEN where STATUS ='P' order by ORDER_ID", $db);
    $OrderWSC_SQL = ibase_query("select * from SHOP_PAIDORDER_LIST_3TEN where STATUS ='C' order by ORDER_ID", $db);

    $status = mb_convert_encoding($OrderRow['STATUS'], "windows-1251", "UTF-8");
    ?>


    <div class="col-12 orderBox notReservation">
        <h3>заказы</h3>
        <div class="col-12 notReservation">
            <div class="row">
                <div class="col-4"><strong>время заказа:</strong></div>
                <div class="col-4"><strong>время доставки:</strong></div>
                <div class="col-4"><strong>сумма заказа:</strong></div>
            </div>
        </div>

        <?php
        while ($OrderWSC = ibase_fetch_assoc($OrderWSC_SQL)) {
            $order_id = $OrderWSC['ORDER_ID'];
            ?>
            <div class="orderHead" id="<?php echo $order_id; ?>">
                <div class="row">
                    <div class="col-4"><?php echo mb_convert_encoding($OrderWSC['ORDER_TIME'], "UTF-8", "windows-1251"); ?></div>
                    <div class="col-4"><?php echo mb_convert_encoding($OrderWSC['DELIVERY_TIME'], "UTF-8", "windows-1251"); ?> </div>
                    <div class="col-4"><?php echo mb_convert_encoding($OrderWSC['ORDER_SUM'], "UTF-8", "windows-1251"); ?>
                        руб.
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <br>
    <div class="col-12 orderBox">
        <h3>завершенные заказы</h3>
        <div class="Done col-12">
            <div class="row">
                <div class="col-4"><strong>номер заказа:</strong></div>
                <div class="col-4"><strong>время заказа:</strong></div>
                <div class="col-4"><strong>сумма заказа:</strong></div>
            </div>
        </div>
        <?php
        while ($OrderWSD = ibase_fetch_assoc($OrderWSD_SQL)) {
            $order_id = $OrderWSD['ORDER_ID'];
            ?>
            <div class="orderHead" id="<?php echo $order_id; ?>">
                <div class="row">
                    <div class="col-4"><?php echo $OrderWSD['ORDER_ID']; ?></div>
                    <div class="col-4"><?php echo $OrderWSD['ORDER_TIME']; ?></div>
                    <div class="col-4"><?php echo $OrderWSD['ORDER_SUM']; ?> Руб.</div>
                </div>
            </div>
        <?php } ?>
    </div>


</div>
</body>
</html>
<script language="JavaScript">
    $(document).ready(function () {
        $('.orderHead').click(function (event) {
            let order_id = this.id;
            document.location.href = 'orderspec.php?order=' + order_id;
        });

    });
</script>

