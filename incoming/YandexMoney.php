<?php

$hash = sha1($_POST['notification_type'] . '&' .
    $_POST['operation_id'] . '&' .
    $_POST['amount'] . '&' .
    $_POST['currency'] . '&' .
    $_POST['datetime'] . '&' .
    $_POST['sender'] . '&' .
    $_POST['codepro'] . '&' .
    'sqotNuZRBC7Bb6MJqSXG8qMo' . '&' .
    $_POST['label']);

if($_POST['sha1_hash'] !== $hash or $_POST['codepro'] === true or $_POST['unaccepted'] === true) exit('error');

file_put_contents('history.php', $_POST['datetime'].' на сумму '. $_POST['amount']. PHP_EOL, FILE_APPEND);

?>