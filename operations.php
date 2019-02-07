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
        $description = mb_convert_encoding($_POST["description"], "windows-1251", "UTF-8");
        if ($_POST['path'] != 'undefined') {
            $path = 'img/' . basename($_POST['path']);
            $path = mb_convert_encoding($path, "windows-1251", "UTF-8");
            $result = ibase_query("UPDATE or INSERT INTO SHOP_PRODUCTS (ARTICUL,NAME,PRICE,PHOTO_PATH,CATEGORY,DESCRIPTION) VALUES('$articul','$name','$price','$path','$category','$description')", $db);
        } else {
            $result = ibase_query("UPDATE or INSERT INTO SHOP_PRODUCTS (ARTICUL,NAME,PRICE,CATEGORY,DESCRIPTION) VALUES('$articul','$name','$price','$category','$description')", $db);
        }

        $DelOrderRes = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ARTICUL = '$articul'", $db);

    }
}
/************************************************************************************************************************************************************/
if ($_POST["operation"] == "ProductAsrtAdd") {
    include('db.php');
    $asrtName = mb_convert_encoding($_POST['name'], "windows-1251", "UTF-8");
    $asrtQuantity = mb_convert_encoding($_POST['quantity'], "windows-1251", "UTF-8");
    $asrtArticul = mb_convert_encoding($_POST['articul'], "windows-1251", "UTF-8");
    $asrt= mb_convert_encoding($_POST['asrt'], "windows-1251", "UTF-8");
    $addAsrtRes = ibase_query("UPDATE or INSERT INTO SHOP_ASRT_3TEN VALUES('$asrtArticul',$asrtQuantity,'$asrtName',$asrt)", $db);
}

/************************************************************************************************************************************************************/
if ($_POST["operation"] == "OrderAdd") {
    include("db.php");
    if (isset($_POST['articul']) && isset($_SESSION['SESSION'])) {

        $articul = mb_convert_encoding($_POST['articul'], "windows-1251", "UTF-8");
        $session = mb_convert_encoding($_SESSION['SESSION'], "windows-1251", "UTF-8");
        $asrtOrder = mb_convert_encoding($_POST['asrt'], "windows-1251", "UTF-8");
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
            $result = ibase_query("UPDATE OR INSERT INTO SHOP_ORDER_3TEN (ARTICUL,SESSION,ID,ORDER_ID,ASRT ) VALUES('$articul','$session','$id',gen_id(SHOP_ORDER_ID_GEN_3TEN,1),'$asrtOrder')", $db);
        } else {
            $result = ibase_query("UPDATE OR INSERT INTO SHOP_ORDER_3TEN (ARTICUL,SESSION,ID,ORDER_ID,ASRT ) VALUES('$articul','$session','$id',$order_id,'$asrtOrder')", $db);
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
if ($_POST["operation"] == "category_dell") {
    include("db.php");
    if (isset($_POST['category'])) {
        $category = mb_convert_encoding($_POST['category'], "windows-1251", "UTF-8");
        $result = ibase_query("DELETE FROM SHOP_CATEGORY_3TEN where CATEGORY = '$category'", $db);
    }
}
/************************************************************************************************************************************************************/
if ($_POST["operation"] == "order_product_quantity_change") {
    include("db.php");
    $order_id = $_POST["order_id"];
    $articul = $_POST["articul"];
    $quantity = $_POST["quantity"];
    $asrt = $_POST["asrt"];
    $result = ibase_query("update shop_order_3ten set QUANTITY = $quantity where ARTICUL = '$articul' and ORDER_ID = $order_id and ASRT = $asrt", $db);

}
/************************************************************************************************************************************************************/
if ($_POST["operation"] == "payment") {
    include("db.php");

    $price = 0;
    $docheadRes = ibase_query("select * from SHOP_DOCHEAD_CREATOR($price,1,1,-1,14)", $db);
    $docheadRow = ibase_fetch_assoc($docheadRes);
    $dochead_id = $docheadRow['OUT_DOCHEAD'];

    $order_id = $_POST['ORDER_ID'];
    $res = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = $order_id ", $db);
    while ($row = ibase_fetch_assoc($res)) {
        $articul = $row['ARTICUL'];
        $quantity = $row['QUANTITY'];
        $docspecCreateRes = ibase_query("select * from SPEC_ADD_ARTICUL('$articul',1,1,$quantity,0,0,$dochead_id,1,1,1,'$articul',0, 0,null)", $db);
        $docspecCreateRow = ibase_fetch_assoc($docspecCreateRes);

        $PriceRes = ibase_query("select PRICE from SHOP_PRODUCTS where ARTICUL = '$articul'", $db);
        $priceRow = ibase_fetch_assoc($PriceRes);
        $PriceProd = $priceRow['PRICE'];
        $updatedocspeacres = ibase_query("update DOCSPEC set PRICERUB = $PriceProd where ARTICUL = $articul  and ID_DOCHEAD = $dochead_id", $db);
        $date = date("d.m.Y h:m:s");
        $OrderKey = rand(10000, 99999);
        $PaidOrder = ibase_query("update or insert into SHOP_PAIDORDER_LIST_3TEN(ORDER_ID,DOCHEAD,ORDER_TIME,STATUS,ORDER_KEY) values($order_id ,$dochead_id,'$date','D','$OrderKey')", $db);
    }

    $result = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ORDER_ID = '$order_id' ", $db);
}
/************************************************************************************************************************************************************/
?>