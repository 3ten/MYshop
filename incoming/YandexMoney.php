<?php
include("../db.php");
$hash = sha1($_POST['notification_type'] . '&' .
    $_POST['operation_id'] . '&' .
    $_POST['amount'] . '&' .
    $_POST['currency'] . '&' .
    $_POST['datetime'] . '&' .
    $_POST['sender'] . '&' .
    $_POST['codepro'] . '&' .
    'BMLTm6BoulnQ3ad/Pk94dDhU' . '&' .
    $_POST['label']);

if ($_POST['sha1_hash'] !== $hash or $_POST['codepro'] === true or $_POST['unaccepted'] === true) {
    exit('error');

}

$price = $_POST['amount'];
$docheadRes = ibase_query("select * from SHOP_DOCHEAD_CREATOR($price,1,1,-1,14)", $db);
$docheadRow = ibase_fetch_assoc($docheadRes);
$dochead_id = $docheadRow['OUT_DOCHEAD'];


$order_id = $_POST['label'];
$res = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = $order_id ", $db);
while ($row = ibase_fetch_assoc($res)) {
    $articul = $row['ARTICUL'];
    $price = $_POST['amount'];
    $quantity = $row['QUANTITY'];
    $docspecCreateRes = ibase_query("select * from SPEC_ADD_ARTICUL('$articul',1,1,$quantity,0,0,$dochead_id,1,1,1,'$articul',0, 0,null)", $db);
    $docspecCreateRow = ibase_fetch_assoc($docspecCreateRes);

    $PriceRes = ibase_query("select PRICE from SHOP_PRODUCTS where ARTICUL = '$articul'", $db);
    $priceRow = ibase_fetch_assoc($PriceRes);
    $PriceProd = $priceRow['PRICE'];
    $PriceProd = $PriceProd * 0.95;
    $updatedocspeacres = ibase_query("update DOCSPEC set PRICERUB = $PriceProd where ARTICUL = $articul  and ID_DOCHEAD = $dochead_id", $db);

    $date = date("d.m.Y h:m:s");
    $OrderKey = rand(10000, 99999);
    $PaidOrder = ibase_query("update or insert into SHOP_PAIDORDER_LIST_3TEN(ORDER_ID,DOCHEAD,ORDER_TIME,STATUS,ORDER_KEY) values($order_id ,$dochead_id,'$date','A','$OrderKey')", $db);


}

$result = ibase_query("DELETE FROM SHOP_ORDER_3TEN WHERE ORDER_ID = '$$order_id'", $db);
?>
