<?php
session_start();
include("db.php");
?>
<html>
<head>
    <meta charset="utf-8">
    <!--Запрет масштабирования-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>
    <title>Derkor</title>
    <!--Возможность добавления на главный экран-->
    <meta name="mobile-web-app-capable" content="yes">
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>

    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">
    <script defer src="fontawesome/js/brands.js"></script>
    <script defer src="fontawesome/js/solid.js"></script>
    <script defer src="fontawesome/js/fontawesome.js"></script>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order_tracking.css">
    <script src="js/search.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
    <script>
        function createOrderList(name, asrtName, price, orderId, quantity, order_sum, status) {
            //console.log(name, ' ', orderId);
            let product = document.createElement('div');
            product.className = 'col-sm-12 orderHead';
            let row = document.createElement('div');
            row.className = 'row';
            let product_name = document.createElement('div'),

                product_price = document.createElement('div'),
                product_quantity = document.createElement('div'),
                order_status = document.createElement('div');

            product_name.className = 'col-sm-5';
            product_price.className = 'col-sm-2';
            product_quantity.className = 'col-sm-2';
            order_status.className = 'col-sm-3';

            product_name.innerText = name + ' ' + asrtName;
            product_price.innerText = price + 'руб.';
            product_quantity.innerText = quantity + 'ШТ';
            if (status === 'C') {
                order_status.innerText = 'ожидайте доставки';
            }


            row.appendChild(product_name);
            row.appendChild(product_price);
            row.appendChild(product_quantity);
            row.appendChild(order_status);
            product.appendChild(row);
            document.getElementById('orderBody').appendChild(product);
        }
    </script>
</head>
<body>
<div class="menu">
    <div class="row">
        <a href="index.php"><span class="menu_logo"><i class="fas fa-store-alt fa-3x"></i></span></a>
        <a href="order.php"><span class="menu_logo"><i class="fas fa-shopping-basket fa-3x"></i></span></a>
        <a href="home.php"><span class="menu_logo"><i class="fas fa-user-circle fa-3x"></i></span></a>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 orderList">
            <div class="row">
                <div class="header col-sm-12">
                    <div class="text-center">Ваши Заказы</div>
                </div>
            </div>
            <div class="row">
                <div class="body col-sm-12" id="orderBody">
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$session = $_SESSION['SESSION'];
$OrderWSD_SQL = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='o$session'", $db);
while ($OrderWSD = ibase_fetch_assoc($OrderWSD_SQL)) {
    $asrt = $OrderWSD['ASRT'];
    $articul = $OrderWSD['ARTICUL'];
    $quantity = $OrderWSD['QUANTITY'];
    $orderId = $OrderWSD['ORDER_ID'];
    if ($asrt == '') {
        $asrt = 'null';
    }
    $getProduct_SQL = ibase_query("select * from SHOP_GET_PRODUCTINFO_3TEN($asrt,'$articul')", $db);
    $getProduct = ibase_fetch_assoc($getProduct_SQL);
    $asrtName = mb_convert_encoding($getProduct['OUT_ASRT_NAME'], "UTF-8", "windows-1251");
    $name = mb_convert_encoding($getProduct['OUT_PROD_NAME'], "UTF-8", "windows-1251");
    $price = mb_convert_encoding($getProduct['OUT_PROD_SUM'], "UTF-8", "windows-1251");

    $getStatus_SQL = ibase_query("select * from SHOP_PAIDORDER_LIST_3TEN where ORDER_ID = $orderId", $db);
    $getStatus = ibase_fetch_assoc($getStatus_SQL);
    $status = $getStatus['STATUS'];
    ?>
    <script>
        createOrderList(
            '<?php echo $name;?>', '<?php echo $asrtName;?>', '<?php echo $price;?>', '<?php echo $orderId;?>', '<?php echo $quantity;?>', '<?php echo $quantity;?>', '<?php echo $status;?>');
    </script>
<?php } ?>

</body>

</html>