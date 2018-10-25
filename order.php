<?php
session_start();
//echo $_SESSION['NAME'];
include("db.php");
$session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");
$res = ibase_query("select ARTICUL from SHOP_ORDER_3TEN where SESSION ='$session'", $db);

?>
<html>
<div class="menu">
    <a href="index.php"> <img src="img/shop.png"> </a>
</div>
</html>
<?php
while (@$row = ibase_fetch_assoc($res)) {
    $articul = $row['ARTICUL'];

    $ProductQuery = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$articul'", $db);
    $product = ibase_fetch_assoc($ProductQuery);
    $articul = mb_convert_encoding($product['ARTICUL'], "UTF-8", "windows-1251");
    if (!empty($articul)) {
        $name = mb_convert_encoding($product['NAME'], "UTF-8", "windows-1251");
        $price = mb_convert_encoding($product['PRICE'], "UTF-8", "windows-1251");
        $path = mb_convert_encoding($product['PHOTO_PATH'], "UTF-8", "windows-1251");
        if (empty($path)) {
            $path = "img/milk.jpg";
        }

        echo
            ' 
 <div class="container">
   <div class="row"> 
       <div id="' . $articul . '" class="col-sm-4"> <img src="' . $path . '"> <h3>' . $name . ' </h3> <p>' . $price . ' </p> </div>   
        </div>
  </div>
';
    }
}


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
</body>
</html>

