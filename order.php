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
    <link rel="stylesheet" href="css/order.css">
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
</head>
<body>
<div class="bg"></div>
<div class="menu">
    <div class="row">
        <a href="index.php"><span class="menu_logo"><i class="fas fa-store-alt fa-3x"></i></span></a>
        <a href="order_tracking.php"> <span class="menu_logo"><i class="fas fa-truck fa-3x"></i></span></a>
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
                if ($row['ASRT'] != null) {
                    $asrt = $row['ASRT'];
                    $asrtRes = ibase_query("select * from SHOP_ASRT_3TEN where ASRT = '$asrt' and ARTICUL = '$articul'", $db);
                    $GetAsrt = ibase_fetch_assoc($asrtRes);
                    $quantityAsrt = $GetAsrt['ASRT_QUANTITY'];

                    $price = $price * $quantityAsrt;
                } else {
                    $asrt = null;
                }


                if (!file_exists($path)) {
                    $path = "img/default.jpg";
                }


                ?>
                <div id="id_<?php echo $articul ?>" class="mainBox col-sm-3 col-xs-12 d-none d-md-block">
                    <div id="<?php echo $articul . $asrt ?>"
                         class="col-12 productBox" data-name="<?php echo $name; ?>"
                         data-articul="<?php echo $articul; ?>"
                         data-asrt="<?php echo $asrt; ?>">

                        <a class="Product_Order_dellBtn"
                           id="<?php echo $articul . $asrt; ?>DellBtn"
                           data-articul="<?php echo $articul; ?>"
                           data-asrt="<?php echo $asrt; ?>"
                           style="color: white">x</a>

                        <img src="<?php echo $path; ?>" id="<?php echo $articul . $asrt; ?>orderIMG"
                             class="img-fluid orderIMG">

                       <h3 class="text scrollbar"><?php echo $name;
                                if ($GetAsrt['ASRT_NAME'] != null) {
                                    echo ' ' . mb_convert_encoding($GetAsrt['ASRT_NAME'], "UTF-8", "windows-1251");;
                                }; ?></h3>
                        <p><?php echo $price; ?> руб.</p>
                        количество: <input type="number"
                                           id="<?php echo $articul . $asrt; ?>quantity"
                                           class="quantity" placeholder="количество"
                                           data-articul="<?php echo $articul; ?>"
                                           data-asrt="<?php echo $asrt; ?>"
                                           value="<?php echo $quantity; ?>"
                                           data-orderid="<?php echo $order_id; ?>"
                        >
                    </div>
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
        /*изменение количества товара*/
        $('.quantity').on('input', function () {
            // alert( this.id.replace(/quantity/g, ''));
            let element = document.getElementById(this.id);
            let quantity = element.value;
            let order_id = element.dataset.orderid;
            console.log('operation=order_product_quantity_change&articul=' + element.dataset.articul + '&order_id=' + order_id + '&quantity=' + quantity + '&asrt=' + element.dataset.asrt);
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=order_product_quantity_change&articul=' + element.dataset.articul + '&order_id=' + order_id + '&quantity=' + quantity + '&asrt=' + element.dataset.asrt,
                success: function (data) {
                    console.log("Добавлено " + quantity + " " + order_id + " " + data);
                }
            });
        });


        $('.col-sm-4').click(function (event) {
            let id = this.id + "orderIMG";
            if (document.getElementById(id).style.display === 'inline') {
                document.getElementById(id).style.display = 'none';
            } else {
                // document.getElementById(id).style.display = 'inline';
            }
        });
    });

</script>
</html>

