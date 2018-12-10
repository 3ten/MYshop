<?php
include("../db.php");
$hash = sha1($_POST['notification_type'] . '&' .
    $_POST['operation_id'] . '&' .
    $_POST['amount'] . '&' .
    $_POST['currency'] . '&' .
    $_POST['datetime'] . '&' .
    $_POST['sender'] . '&' .
    $_POST['codepro'] . '&' .
    'sqotNuZRBC7Bb6MJqSXG8qMo' . '&' .
    $_POST['label']);

if ($_POST['sha1_hash'] !== $hash or $_POST['codepro'] === true or $_POST['unaccepted'] === true) exit('error');

file_put_contents('history.php', $_POST['datetime'] . ' на сумму ' . $_POST['amount'] . PHP_EOL, FILE_APPEND);
if (!empty($_POST['label'])) {
    $order_id = mb_convert_encoding($_POST['operation_id'], "windows-1251", "UTF-8");
    $res = ibase_query("select * from SHOP_ORDER_3TEN where ORDER_ID = '$order_id' ", $db);

    $docheadRes = ibase_query("select * from SHOP_DOCHEAD_CREATOR(1,1,-1,107)", $db);
    $docheadRow = ibase_fetch_assoc($docheadRes);
    $dochead_id = $docheadRow['OUT_DOCHEAD'];
    while ($row = ibase_fetch_assoc($res)) {
        $articul = $row['articul'];
        $price = $_POST['amount'];
        $quantity = $row['QUANTITY'];
        $docspecCreateRes = ibase_query("select * from SPEC_ADD_ARTICUL('$articul',1,1,$quantity,$price,0,$dochead_id,1,1,1,'$articul',null, null)", $db);
    }
    $date = date("d.m.Y h:m:s");
    $OrderKey = "test12";
    $PaidOrder = ibase_query("insert into SHOP_PAIDORDER_3TEN values($order_id ,$dochead_id ,'',$date,'A','','$OrderKey',null) ", $db);

}
?>