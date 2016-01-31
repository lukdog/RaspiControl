<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * User: Luca Doglione
 * Date: 27/11/15
 * Time: 15:10
 */
include_once "./classes/Script.php";
include_once "./functions/functions.php";
session_start();

if (!isset($_SESSION['USERNAME']))
{
    echo "Sessione non registrata";
    header("location:login.php");
} else{
    $utente = new User($_SESSION['USERNAME']);
    if(!$utente->IsAdmin()){
        echo "Non hai privilegi di Admin";
        header("location:index.php");
    }
}

?>

<html>
<head>
    <title>raspiControl</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <meta name="msapplication-tap-highlight" content="no" />
    <!-- Stylesheet jquery e mio !-->
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
<div class="pagina paginanewuser" data-role="page" data-theme="b">

    <div data-role="header">
        <h1>Control</h1>
    </div>

    <div data-role="main" id="loginContent" class="ui-content">
        <form class="out newuser" action="newuser.php" method="POST" data-ajax="false">
            <label for="username">Nome Utente:</label>
            <input data-theme="b" id="username" data-corners="false" type="text" name="username" value="" placeholder="Username"/>
            <label for="pwd1">Password:</label>
            <input data-theme="b" id="pwd1" data-corners="false" type="password" name="password" value ="" placeholder="Password"/>
            <label for="pwd2">Ripeti Password:</label>
            <input data-theme="b" id="pwd2" data-corners="false" type="password" name="password2" value ="" placeholder="Ripeti Password"/>
            <button class="ui-btn-login ui-btn " type="submit">
                SALVA
            </button>
        </form>
    </div>

    <div data-role="footer">
        <div data-role="navbar">
            <ul>
                <li>
                    <a href="tools.php">Indietro</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script> -->
</body>
</html>