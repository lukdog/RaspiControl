<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 04/10/14
 * Time: 17:02
 */
session_start();
include_once dirname(__FILE__) . "/functions/functions.php";

if (isset ($_SESSION['USERNAME']))
{
    //Redirection to Index
    //TODO control time of session
    redirect("index.php", 302);
    exit;

} else if (isset($_POST['USERLOGIN']) && isset($_POST['PASSWDLOGIN']))
{
    try
    {
        if (checklogin($_POST['USERLOGIN'], $_POST['PASSWDLOGIN']))
        {
            //Build of session and redirect to index
            $_SESSION['USERNAME'] = strtolower(trim($_POST['USERLOGIN']));
            $_SESSION['LSTTIME'] = time();
            redirect("index.php", 302);
            exit;
        }
    } catch (Exception $e)
    {
        $error = $e->getMessage();
    }
}
?>

<html>
<head>
    <title>LOGIN</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <!--Jquery Mobile Online !-->
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css"/>
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

        <form class="login" action="login.php" method="POST" data-ajax="false">
            <div class="logo"></div>
            <input data-theme="b" id="username" data-corners="false" type="text" name="USERLOGIN" value=""
                   placeholder="Username"/>
            <input data-theme="b" data-corners="false" type="password" name="PASSWDLOGIN" value=""
                   placeholder="Password"/>
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