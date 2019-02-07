<?php
session_start();

$paymentType = $_POST['paymentType'];
$targets = $_POST['targets'];
$label = $_POST['label'];
$sum = $_POST['sum'];

$phone = $_POST['phone'];
$city = $_POST['city'];
$address = $_POST['address'];
$delivery_time = $_POST['DT'];
$date = date("d.m.Y h:m:s");
include("db.php");
$PaidOrder = ibase_query("update or insert into SHOP_PAIDORDER_LIST_3TEN values($label,-1,'$phone','$date','W',null,'-1','$address','$delivery_time',$sum,null) ", $db);
if ($paymentType == "CP") {
    $PaidOrder = ibase_query("update SHOP_PAIDORDER_LIST_3TEN set STATUS = 'C'", $db);
    $session = $_SESSION['SESSION'];
    $PaidOrder = ibase_query("update SHOP_ORDER_3TEN set session = 'o$session' where ORDER_ID = '$label'", $db);
    header("Location: success.php");
} else {

    ?>

    <html>
    <form id="foobar" method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
        <input type="hidden" name="receiver" value="410018309644744">
        <input type="hidden" name="quickpay-form" value="shop">
        <input type="hidden" name="targets" value="<?php echo $targets; ?>">
        <input type="hidden" name="label" value="<?php echo $label; ?>">
        <input type="hidden" name="sum" value="<?php echo $sum; ?>" data-type="number">
        <label><input type="hidden" name="paymentType" value="<?php echo $paymentType; ?>"></label>
        <input type="hidden" name="successURL" value="http://derkor.ru/shop/success.php">
    </form>
    </html>

    <script>
        setTimeout(function () {
            document.getElementById('foobar').submit();
        }, 0);
    </script>
    <?php
}
?>