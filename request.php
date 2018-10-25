<?php
session_start();

if(isset($_POST['name']) && isset($_SESSION['SESSION'])){

    $articul = mb_convert_encoding($_POST['articul'], "windows-1251", "UTF-8");
    $session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");

    $result=ibase_query("UPDATE or INSERT INTO SHOP_PRODUCTS (ARTICUL,NAME,PRICE) VALUES('$articul','$name','$price')",$db);
}

?>
