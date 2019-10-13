<?php
session_start();


$order_id = $_POST['order_id'];
$sum = $_POST['sum'];

$clientNameWin = mb_convert_encoding($_POST['clientName'], "windows-1251", "UTF-8");
$order_idWin = mb_convert_encoding($_POST['order_id'], "windows-1251", "UTF-8");
$phoneWin = mb_convert_encoding($_POST['phone'], "windows-1251", "UTF-8");
$cityWin = mb_convert_encoding($_POST['city'], "windows-1251", "UTF-8");
$addressWin = mb_convert_encoding($_POST['address'], "windows-1251", "UTF-8");
$delivery_timeWin = mb_convert_encoding($_POST['DT'], "windows-1251", "UTF-8");
$dateWin = mb_convert_encoding(date("d.m.Y h:m:s"), "windows-1251", "UTF-8");
$commentWin = mb_convert_encoding( htmlspecialchars($_POST['comment']), "windows-1251", "UTF-8");
$order_id = mb_convert_encoding($order_id, "windows-1251", "UTF-8");
$emailWin = 'null';

include("db.php");
//ORDER_ID,DOCHEAD,ORDER_TIME,ORDER_SUM,STATUS,DELIVERY_TIME,CLIENT_NUMBER,CLIENT_NAME,CLIENT_ADDRESS,EMAIL,COMMENT,ORDER_KEY
$PaidOrder = ibase_query("update or insert into SHOP_PAIDORDER_LIST_3TEN values($order_idWin,-1,'$dateWin',$sum,'C','$delivery_timeWin','$phoneWin','$clientNameWin','$addressWin','$emailWin','$commentWin','-1') ", $db);
$session = $_SESSION['SESSION'];
$PaidOrder = ibase_query("update SHOP_ORDER_3TEN set session = 'o$session' where ORDER_ID = '$order_id'", $db);
header("Location: order_tracking.php");


?>