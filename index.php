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
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>

</head>

<body>



<div class="container">
    <div class="row">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {
            $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
            $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");
            $price = mb_convert_encoding($row['PRICE'], "UTF-8", "windows-1251");
            $session = $_SESSION['SESSION'];

            $orderres = ibase_query("select * from SHOP_ORDER_3TEN where SESSION ='$session'", $db);
            $OrderRow = ibase_fetch_assoc($orderres);
            if ($articul != mb_convert_encoding($OrderRow["ARTICUL"], "UTF-8", "windows-1251")) {
                $IsOrderText = "добавить в корзину";
                $IsOrder = "false";
            } else {
                $IsOrderText = "в корзине";
                $IsOrder = "true";
            }
            echo
                ' 
       <div id="' . $articul . '" class="col-sm-4" data-isorder="' . $IsOrder . '"> 
       <img src="img/milk.jpg"> 
       <h3>' . $name . ' </h3> 
       <p>' . $price . ' Р. </p> 
       <p>' . $IsOrderText . ' </p>
       </div>          
                ';
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
