<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 04/10/14
 * Time: 16:37
 */
session_start();
include_once dirname(__FILE__) . "/classes/User.php";
include_once dirname(__FILE__) . "/classes/Script.php";
include_once dirname(__FILE__) . "/functions/functions.php";

if (!isset($_SESSION['USERNAME']))
{
    header("location:login.php");
}
if (isset($_GET['LOGOUT']))
{
    $_SESSION = array();
    if (ini_get("session.use_cookies"))
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600 * 24, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
    redirect("login.php", 301);
    exit;
} else
{
    //TODO control of session duration
    $utente = new User($_SESSION['USERNAME']);

}

?>

<html>
<head>
    <title>raspiControl</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <!-- Stylesheet jquery e mio !-->
    <!-- <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css"/> -->
    <link rel="stylesheet" href="style/styles.css"/>
    <!--Google Fonts !-->
    <link href='http://fonts.googleapis.com/css?family=Economica:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="style/images/favicon.ico">
    <link rel="icon" href="style/images/icon.png" type="image/png"/>
    <link rel="apple-touch-icon" href="style/images/icon.png" type="image/png"/>
    <link rel="mask-icon" color="#d6264f" href="style/images/iconP.svg">
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" onresize="setFooterWidth()"
      onload="setFooterWidth()">
<div class="pagina index">

    <header>
        RaspiControl
    </header>

    <section class="mainMenu">

        <?php
        try
        {
            CreaForm($utente);
        } catch (Exception $e)
        {
            echo "<p class='error'>" . $e->getMessage() . "</p>";
        }


        ?>

        <p class="category" id="General_btn" onclick="showPanel(this)">
            General
        </p>
        <ul class="scripts" id="General_Panel">
            <li id="1" about="vuoi davvero spegnere il raspberry?" onclick="execCmd(this)">Spegni</li>
            <li>Riavvia</li>
            <li>Prova</li>
        </ul>
        <p class="category">
            Storage
        </p>
        <p class="category">
            Network
        </p>

    </section>
    <footer>

        <ul>
            <li class="footerTab">
                <a href="tools.php">TOOLS</a>
                </li>
            <li class="footerTab">
                <a href="index.php?LOGOUT">LOGOUT</a>
                </li>
            </ul>

    </footer>
</div>

<script src="scripts/jquery.js"></script>
<script src="scripts/function.js"></script>
</body>
</html>
