<?php
session_start();
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
        <link rel="stylesheet" href="css/index.css">

        <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
        <script src="js/jquery.min.js"></script>
        <script async src="js/main.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    </head>
    </html>
<?php

$OrderRes = ibase_query("select * from SHOP_PAIDORDER_3TEN", $db);

while ($OrderRow = ibase_fetch_assoc($OrderRes)) {
    ?>
    <html>
    <body>
    <div class="container">
        <div class="col-sm-4">
            <p> номер заказа:  <?php echo $OrderRow['ORDER_ID']; ?> </p>
            <p> номер клиента:  <?php echo $OrderRow['CLIENT_NUMBER']; ?> </p>
            <p> время закза: <?php echo $OrderRow['ORDER_TIME']; ?> </p>
            <p> Статус: <?php echo $OrderRow['STATUS']; ?> </p>
            <p> почта клиента:  <?php echo $OrderRow['EMAIL']; ?> </p>
            <p> ключ клиента: <?php echo $OrderRow['ORDER_KEY']; ?> </p>
            <p>   <?php echo $OrderRow['COMMENT']; ?> </p>
        </div>
    </div>
    </div>
    </body>
    </html>
    <?php

}

?>