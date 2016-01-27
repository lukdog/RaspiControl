<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 04/10/14
 * Time: 17:02
 */

if(isset ($_SESSION['usernameLogin'])){
    header("location:index.php");
}

?>

<html>
<head>
    <title>LOGIN</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <meta name="msapplication-tap-highlight" content="no" />
    <!--Jquery Mobile Online !-->
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
    <link rel="stylesheet" href="style/style.css"/>
    <!--Google Fonts !-->
    <link href='http://fonts.googleapis.com/css?family=Economica:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="style/images/favicon.ico">
    <link rel="icon" href="style/images/icon.png" type="image/png"/>
    <link rel="apple-touch-icon" href="style/images/icon.png" type="image/png"/>
    <link rel="mask-icon" color="#d6264f" href="style/images/iconP.svg">
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div class="pagina" data-role="page" data-theme="b">
    <div data-role="main" id="loginContent" class="ui-content">

        <form class="login" action="scripts/logincontrol.php" method="POST" data-ajax="false" >
            <div class="logo"></div>
            <input data-theme="b" id="username" data-corners="false" type="text" name="username" value="" placeholder="Username"/>
            <input data-theme="b" data-corners="false" type="password" name="password" value ="" placeholder="Password"/>
            <button class="ui-btn-login ui-btn " type="submit">
                LOGIN
            </button>


        </form>
    </div>
</div>

<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</body>
</html>