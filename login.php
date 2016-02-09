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
} else if (isset($_GET['EXPIRED']))
{
    $error = "Session expired";
}
?>

<html>
<head>
    <title>raspicontrol</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!--Jquery Mobile Online !-->
    <!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css"/>-->
    <link rel="stylesheet" href="style/style.css"/>
    <!--Google Fonts !-->
    <link href='http://fonts.googleapis.com/css?family=Economica:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="style/images/favicon.ico">
    <link rel="icon" sizes="128x128" href="style/images/android.png" type="image/png"/>
    <link rel="icon" sizes="192x192" href="style/images/android-hd.png" type="image/png"/>
    <link rel="apple-touch-icon" sizes="120x120" href="style/images/apple-iphone.png" type="image/png"/>
    <link rel="apple-touch-icon" sizes="152x152" href="style/images/apple-ipad.png" type="image/png"/>
    <link rel="mask-icon" color="#d6264f" href="style/images/iconP.svg">
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div class="page login">

    <form class="login" action="login.php" method="POST" data-ajax="false" id="login_Form">
        <p class="logo"></p>

        <?php if (isset($error))
        {
            echo "<p class='error'>" . $error . "</p>";
        }
        ?>
        <p class="input">
            <input id="username" data-corners="false" type="text" name="USERLOGIN" value=""
                   placeholder="Username" onkeypress="return submitOnEnter(event, 'login_Form')"/>
            <input data-corners="false" type="password" name="PASSWDLOGIN" value=""
                   placeholder="Password" onkeypress="return submitOnEnter(event, 'login_Form')"/>
        </p>
        <p class="input button" id="login" onclick="submitForm(this)">LOGIN</p>
    </form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="scripts/function.js"></script>
<!--<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>-->
</body>
</html>