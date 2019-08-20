<?php
session_start();
include('db.php');
if (!empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['passCheck'])) {
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $passCheck = $_POST['passCheck'];
    if ($passCheck == $pass) {
        $checkLoginSQL = ibase_query("select * from SHOP_USERS_3TEN WHERE LOGIN = '$login'", $db);
        $checkLogin = ibase_fetch_assoc($checkLoginSQL);
        if ($checkLogin['LOGIN'] != $login) {
            $pass = sha1($pass);
            $loginAddSQL = ibase_query("insert into SHOP_USERS_3TEN(ID,LOGIN,ROLE) values (gen_id(SHOP_USER_ID_GEN_3TEN,1),'$login',1)", $db);
            $loginPassAddSQL = ibase_query("insert into SHOP_PASSWORD_3TEN values (gen_id(SHOP_USER_ID_GEN_3TEN,0),'$login','$pass')", $db);
            header("Location: home.php");
        } else {
            echo 'такой логин уже сушествует';
        }
    } else {
        echo "пароли не совпадают";
    }
}
?>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reg.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12 main">
            <div class="form">
                <form method="post" action="reg.php">
                    <h3>Придумайте логин:</h3>
                    <p><input class="text" name="login" placeholder="Логин" required></p>
                    <h3>Придумайте пароль:</h3>
                    <p><input class="text" type="password" name="pass" placeholder="Пароль" required></p>
                    <h3>Повторите пароль:</h3>
                    <p><input class="text" type="password" name="passCheck" placeholder="Повторите пароль" required></p>
                    <p><input class="button" type="submit" value="Зарегистрироваться"></p>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
