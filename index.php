<?php
session_start();
include("db.php");
$res = ibase_query("select * from SHOP_PRODUCTS", $db);
//$row = ibase_fetch_assoc($res);
if (empty($_SESSION['SESSION'])) {
    $_SESSION['SESSION'] = rand(100000, 999999);
}
include("menu.php");
?>
<!DOCTYPE html>
<html>
<head>
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

</head>

<body>

	<!--<h1 align="center" style="color: #AA2277">KAK TEbE TAKOE ILON MASK?</h1>-->

<div class="container">
    <div class="row">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {
            $articul = $row['ARTICUL'];
            $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
            $price = mb_convert_encoding($row['PRICE'], "UTF-8", "windows-1251");
            $session = $_SESSION['SESSION'];
            $path = mb_convert_encoding($row['PHOTO_PATH'], "UTF-8", "windows-1251");
            if (!file_exists($path)) {
                $path = "img/default.jpg";
            }
            $orderres = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='$session' and ARTICUL = '$articul'", $db);
            $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
            $OrderRow = ibase_fetch_assoc($orderres);
            //if ($articul != mb_convert_encoding($OrderRow["ARTICUL"], "UTF-8", "windows-1251")) {
            if (empty($OrderRow["ARTICUL"])) {
                $IsOrderText = "добавить в корзину";
                $IsOrder = "false";
            } else {
                $IsOrderText = "в корзине";
                $IsOrder = "true";
            }
            ?>

            <div id="<?php echo $articul ?>" class="col-sm-4" data-isorder="' . $IsOrder . '">
                <img src="<?php echo $path ?>" class="img-fluid">
                <h3><?php echo $name ?> </h3>
                <p><?php echo $price ?> Р. </p>
                <p><?php echo $IsOrderText ?> </p>
            </div>

            <?php
        }
        ?>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {
        $('.col-sm-4').click(function () {

            var el = document.getElementById(this.id);
            var IsOrder = el.dataset.isorder;
            if (IsOrder === "true") {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=OrderDell&articul=' + this.id,
                    success: function (data) {
                        alert("Удалено");
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=OrderAdd&articul=' + this.id,
                    success: function (data) {
                        alert("Добавлено");
                        location.reload();
                    }
                });
            }
        });
    });

</script>


</html>
