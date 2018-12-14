<?php
session_start();

/************************************************************************************************************************************************************/
if ($_POST["operation"] == "ProductAdd") {
    include("db.php");
    if (isset($_POST["articul"]) && isset($_POST["name"]) && isset($_POST["price"])) {
        $price = mb_convert_encoding($_POST["price"], "windows-1251", "UTF-8");
        $articul = mb_convert_encoding($_POST["articul"], "windows-1251", "UTF-8");
        $name = mb_convert_encoding($_POST["name"], "windows-1251", "UTF-8");
        $category = mb_convert_encoding($_POST["category"], "windows-1251", "UTF-8");
        $path = 'img/' . basename($_POST['path']);
        if (!file_exists($path)) {
            $path = "img/default.jpg";
        }
        $path = mb_convert_encoding($path, "windows-1251", "UTF-8");
        $result = ibase_query("UPDATE or INSERT INTO SHOP_PRODUCTS (ARTICUL,NAME,PRICE,PHOTO_PATH,CATEGORY) VALUES('$articul','$name','$price','$path','$category')", $db);
        $DelOrderRes = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ARTICUL = '$articul'", $db);

    }
}
/************************************************************************************************************************************************************/

if ($_POST["operation"] == "OrderAdd") {
    include("db.php");
    if (isset($_POST['articul']) && isset($_SESSION['SESSION'])) {

        $articul = mb_convert_encoding($_POST['articul'], "windows-1251", "UTF-8");
        $session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");

        $id = mb_convert_encoding(rand(1000, 9999), "windows-1251", "UTF-8");
        $result = ibase_query("select * from SHOP_PRODUCTS where ARTICUL ='$id'", $db);
        $shoprow = ibase_fetch_assoc($result);

        while ($id == mb_convert_encoding($shoprow["ID"], "UTF-8", "windows-1251")) {
            $id = mb_convert_encoding(rand(1000, 9999), "windows-1251", "UTF-8");
        }

        $getorderid = ibase_query("select ORDER_ID from SHOP_ORDER_3TEN where SESSION  = '$session'", $db);
        $order_idrow = ibase_fetch_assoc($getorderid);

        $order_id = $order_idrow['ORDER_ID'];
        if (empty($order_idrow['ORDER_ID'])) {
            $result = ibase_query("UPDATE OR INSERT INTO SHOP_ORDER_3TEN (ARTICUL,SESSION,ID,ORDER_ID ) VALUES('$articul','$session','$id',gen_id(SHOP_ORDER_ID_GEN_3TEN,1))", $db);
        } else {
            $result = ibase_query("UPDATE OR INSERT INTO SHOP_ORDER_3TEN (ARTICUL,SESSION,ID,ORDER_ID ) VALUES('$articul','$session','$id',$order_id)", $db);
        }

    }
}

/************************************************************************************************************************************************************/

if ($_POST["operation"] == "ProductDell") {
    include("db.php");
    if (isset($_POST["articul"])) {
        $articul = mb_convert_encoding($_POST["articul"], "windows-1251", "UTF-8");

//$articul = mb_convert_encoding("00001", "windows-1251", "UTF-8");
        $result = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ARTICUL = '$articul'", $db);
        $result = ibase_query("DELETE FROM SHOP_PRODUCTS WHERE ARTICUL = '$articul'", $db);
    }
}

/************************************************************************************************************************************************************/

if ($_POST["operation"] == "OrderDell") {
    include("db.php");
    if (isset($_POST["articul"])) {
        $articul = mb_convert_encoding($_POST["articul"], "windows-1251", "UTF-8");

//$articul = mb_convert_encoding("00001", "windows-1251", "UTF-8");
        $result = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ARTICUL = '$articul'", $db);
    }
}

/************************************************************************************************************************************************************/
if ($_POST["operation"] == "category_add") {
    include("db.php");
    if (isset($_POST['category'])) {
        $category = mb_convert_encoding($_POST['category'], "windows-1251", "UTF-8");
        $result = ibase_query("UPDATE OR INSERT INTO SHOP_CATEGORY_3TEN (CATEGORY) VALUES('$category')", $db);
    }
}
/************************************************************************************************************************************************************/
if ($_POST["operation"] == "order_product_quantity_change") {
    include("db.php");
    $order_id = $_POST["order_id"];
    $articul = $_POST["articul"];
    $quantity = $_POST["quantity"];
    $result = ibase_query("update shop_order_3ten set QUANTITY = $quantity where ARTICUL = '$articul' and ORDER_ID = $order_id", $db);

}
/************************************************************************************************************************************************************/
?>