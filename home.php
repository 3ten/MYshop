<?php
session_start();
if ($_SESSION['LOGIN'] == '') {
    header("Location: login.php");
}
include('db.php');
$id = $_SESSION['ID'];
$GetUsersInfoSQL = ibase_query("select * from SHOP_USERS_3TEN where ID = $id");
$GetUsersInfo = ibase_fetch_assoc($GetUsersInfoSQL);

$name = mb_convert_encoding($GetUsersInfo['NAME'], "UTF-8", "windows-1251");
$city = mb_convert_encoding($GetUsersInfo['CITY'], "UTF-8", "windows-1251");
$adds = mb_convert_encoding($GetUsersInfo['ADDRESS'], "UTF-8", "windows-1251");
$email = mb_convert_encoding($GetUsersInfo['EMAIL'], "UTF-8", "windows-1251");
$phone = mb_convert_encoding($GetUsersInfo['NUMBER'], "UTF-8", "windows-1251");

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no"/>

    <link href="fontawesome/css/all.css" rel="stylesheet">
    <script defer src="fontawesome/js/all.js"></script>
    <link href="fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="fontawesome/css/brands.css" rel="stylesheet">
    <link href="/fontawesome/css/solid.css" rel="stylesheet">
    <script defer src="fontawesome/js/brands.js"></script>
    <script defer src="fontawesome/js/solid.js"></script>
    <script defer src="fontawesome/js/fontawesome.js"></script>


    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">

    <!-- <script src="js/jquery-3.3.1.min.js"></script> -->
    <script src="js/jquery.min.js"></script>
    <script async src="js/main.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" media="screen and (max-device-width:500px)" href="css/mobile.css"/>
</head>

<body>
<div class="bg"></div>
<div class="menu">
    <div class="row">
        <a href="index.php"><span class="menu_logo"><i class="fas fa-store-alt fa-3x"></i></span></a>
        <?php if ($_SESSION['ROLE'] == '0') { ?>
            <a href="admin.php"><span class="menu_logo"><i class="fas fa-user-cog fa-3x"></i></span></a>
        <?php } ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 offset-sm-3 ">
            <div class="col-12 main">
                <b>Ваши данные</b><br>

                <b>ФИО</b>
                <div class="row">
                    <label for="name" class="textLabel">
                        <input id="name" class="text" type="text" value="<?php echo $name; ?>">
                    </label>
                </div>

                <b>Город</b>
                <div class="row">
                    <label for="city" class="textLabel">
                        <input id="city" class="text" type="text" value="<?php echo $city; ?>">
                    </label>
                </div>

                <b>Адрес</b>
                <div class="row">
                    <label for="address" class="textLabel">
                        <input id="address" class="text" type="text" value="<?php echo $adds; ?>">
                    </label>
                </div>

                <b>E-mail</b>
                <div class="row">
                    <label for="email" class="textLabel">
                        <input id="email" class="text" type="text" value="<?php echo $email; ?>">
                    </label>
                </div>

                <b>номер</b>
                <div class="row">
                    <label for="phone" class="textLabel">
                        <input id="phone" class="text" type="text" value="<?php echo $phone; ?>">
                    </label>
                </div>
                <br>
                <button class="saveInfBtn button">Сохранить</button>
            </div>

        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {
        $(".saveInfBtn").click(function () {
            let name = document.getElementById('name').value,
                city = document.getElementById('city').value,
                adds = document.getElementById('address').value,
                email = document.getElementById('email').value,
                phone = document.getElementById('phone').value,
                id = <?php echo $_SESSION['ID'];?>;
            $.ajax({
                type: 'POST',
                url: 'operations.php',
                data: 'operation=savePersonInf&id=' + id + '&name=' + name + '&city=' + city + '&adds=' + adds + '&email=' + email + '&phone=' + phone,
                success: function (data) {
                    alert("Сохранено");
                    console.log(data);
                }
            });
        });
    });

</script>
</html>