<?php
session_start();
if (($_POST["login"] != null) && ($_POST["pass"] != null)) {

    $login = $_POST["login"];
    $password = $_POST["pass"];
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login);
    $password = trim($password);

    if ($login == "admin" && $password == "farmshop") {
        $_SESSION['NAME'] = 'admin';
        header("Location: admin.php");
    } else {
        exit ("Извините, введённый вами login или пароль неверный.");
    }

}
?>

<html>
<head>
    <title>Admin panel</title>
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
