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

$docheadRes = ibase_query("select * from SHOP_DOCHEAD_CREATOR(1,1,-1,14)", $db);

$docheadRow = ibase_fetch_assoc($docheadRes);
$dochead_id = $docheadRow['OUT_DOCHEAD'];

$order_id = $_POST['label'];
$res = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = $order_id ", $db);
file_put_contents('history.php', " 1" . PHP_EOL, FILE_APPEND);
while ($row = ibase_fetch_assoc($res)) {
    $articul = $row['ARTICUL'];
    $price = $_POST['amount'];
    $quantity = $row['QUANTITY'];
    $docspecCreateRes = ibase_query("select * from SPEC_ADD_ARTICUL('$articul',1,1,$quantity,$price,0,$dochead_id,1,1,1,'$articul',null, null,null)", $db);
    file_put_contents('history.php', "3" . PHP_EOL, FILE_APPEND);
    $date = date("d.m.Y h:m:s");
    $OrderKey = "test12";
    $PaidOrder = ibase_query("insert into SHOP_PAIDORDER_3TEN values($order_id ,$dochead_id ,'',$date,'A','','$OrderKey',null) ", $db);
    file_put_contents('history.php', $PaidOrder . "4" . PHP_EOL, FILE_APPEND);

}
?>