<?php
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
$PaidOrder = ibase_query("update or insert into SHOP_PAIDORDER_LIST_3TEN values($label,-1,'$phone','$date','W',null,'-1','$address','$delivery_time',$sum) ", $db);
if ($paymentType == "CP") {
    header("Location: success.php");
} else {


    ?>

    <html>
    <form id="foobar" method="POST" action="https://money.yandex.ru/quickpay/confirm.xml">
        <input type="hidden" name="receiver" value="410016725577528">
        <input type="hidden" name="quickpay-form" value="shop">
        <input type="hidden" name="targets" value="<?php echo $targets; ?>">
        <input type="hidden" name="label" value="<?php echo $label; ?>">
        <input type="hidden" name="sum" value="<?php echo $sum; ?>" data-type="number">
        <label><input type="hidden" name="paymentType" value="<?php echo $paymentType; ?>"></label>
        <input type="hidden" name="successURL" value="http://derkor.ru:33888/shop/success.php">
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