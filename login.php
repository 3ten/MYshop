<?php
session_start();
include('db.php');
if (($_POST["login"] != null) && ($_POST["pass"] != null)) {

    $login = $_POST["login"];
    $password = $_POST["pass"];
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login);
    $password = trim($password);

    if ($login != "" && $password != "") {
        $loginCheckSQL = ibase_query("select * from SHOP_PASSWORD_3TEN where LOGIN ='$login' ", $db);
        $loginCheck = ibase_fetch_assoc($loginCheckSQL);
        if ($loginCheck['LOGIN'] == $login && sha1($password) == $loginCheck['PASSWORD']) {
            $loginInfoSQL = ibase_query("select * from SHOP_USERS_3TEN where LOGIN ='$login' ", $db);
            $loginInfo = ibase_fetch_assoc($loginInfoSQL);
            $_SESSION['LOGIN'] = $login;
            $_SESSION['ROLE'] = $loginInfo['ROLE'];
            $_SESSION['ID'] = $loginInfo['ID'];
            $_SESSION['SESSION'] =$loginInfo['ID'];
            header("Location: home.php");
        } else{
            exit ("Извините, введённый вами login или пароль неверный.");
        }

    } else {
        exit ("Извините, введённый вами login или пароль неверный.");
    }

}
?>

<html>
<head>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <form action="login.php" method="post">
                <h3>Login</h3>
                <p><input class="textbox" name="login"></p>
                <h3>Password</h3>
                <p><input class="textbox" type="password" name="pass"></p>
                <p><input class="button" type="submit" value="Войти"></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
