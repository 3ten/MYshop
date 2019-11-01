<?php
/*$database = 'D:\Datakrat\Database\Datakrat.fdb'; */
//$database = 'D:\Datakrat\Database\Demo.fdb';

$database = 'D:\Datakrat\Database\MY_BASE.FDB';
//$database = 'D:\Datakrat\Database\MY_BASE.FDB';
$user = 'SYSDBA';
$password = 'masterkey';
$db = ibase_connect($database, $user, $password);

//$dbm = mysqli_connect ("127.0.0.1:3306","seva","1234","seva");

?>