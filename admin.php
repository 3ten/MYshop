<?php
session_start();
include("db.php");
$res = ibase_query("select articul, name from CARDSCLA WHERE CLASSIF > -1", $db);
//$row = ibase_fetch_assoc($res);
$i = 0;

$order = array();


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
<div class="menu">
    <a href="index.php"> <img src="img/shop.png"> </a>
</div>

<div class="container">
    <div class="row">
        <?php
        while (@$row = ibase_fetch_assoc($res)) {

            $articul = $row['ARTICUL'];
            $result = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
            $shoprow = ibase_fetch_assoc($result);
            if (empty(mb_convert_encoding($shoprow["ARTICUL"], "UTF-8", "windows-1251"))) {
                $InShopText = "добавить в магазин";
                $InShop = "false";
            } else {
                $InShopText = "в магазине";
                $InShop = "true";
            }

            $articul = mb_convert_encoding($row['ARTICUL'], "UTF-8", "windows-1251");
            $name = mb_convert_encoding($row['NAME'], "UTF-8", "windows-1251");

            echo
                ' 
       <div  class="col-sm-4"> 
       <img src="img/milk.jpg"> 
       <h3>' . $name . ' </h3> 
       <p>' . $articul . ' </p>
       <p> ' . $InShopText . ' </p>
      <p> <input type="text" id ="' . $articul . 'txt"> </p>
      <p> <input type="button" id="' . $articul . '" data-name="' . $name . '" data-price="' . $price . '" data-inshop="' . $InShop . '" class="button" value="добавить"> </p>
       </div> 
                
                ';
        }
        ?>
    </div>
</div>
</body>

<script>
    $(document).ready(function () {

        $('.button').click(function () {
            var el = document.getElementById(this.id);
            var name = el.dataset.name;
            var price = document.getElementById(this.id + "txt").value;
            var InShop = el.dataset.inshop;
            if (InShop === "true") {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=ProductDell&articul=' + this.id,
                    success: function (data) {
                        alert("Удалено");
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: 'operations.php',
                    data: 'operation=ProductAdd&articul=' + this.id + '&name=' + name + '&price=' + price,
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