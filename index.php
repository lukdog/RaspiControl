<?php

session_start();
include_once dirname(__FILE__) . "/classes/User.php";
include_once dirname(__FILE__) . "/classes/Script.php";
include_once dirname(__FILE__) . "/functions/functions.php";

if (!isset($_SESSION['USERNAME']))
{
    redirect("login.php", 301);
} else if (isset($_GET['LOGOUT']))
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
    try
    {
        $user = new User($_SESSION['USERNAME']);
    } catch (Exception $e)
    {
        $error = $e->getMessage();
    }

}

?>

<html>
<head>
    <title>raspiControl</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <link rel="stylesheet" href="style/style.css"/>
    <!--Google Fonts !-->
    <link href='http://fonts.googleapis.com/css?family=Economica:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="style/images/favicon.ico">
    <link rel="icon" href="style/images/icon.png" type="image/png"/>
    <link rel="apple-touch-icon" href="style/images/icon.png" type="image/png"/>
    <link rel="mask-icon" color="#d6264f" href="style/images/iconP.svg">
</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" onresize="setFooterWidth()"
      onload="setFooterWidth()">
<div class="page index">

    <header>
        RaspiControl
    </header>

    <section class="mainMenu mainSection">

        <?php
        if (isset($error))
        {
            echo "<p class='error'>" . $error . "</p>";
        } else
        try
        {
            buildMenu($user);
        } catch (Exception $e)
        {
            echo "<p class='error'>" . $e->getMessage() . "</p>";
        }

        ?>

    </section>
    <section class="toolsMenu">
        <ul class="scripts">
            <!-- TODO define Tools Menu -->
            <li><a href="passwd.php">Change Password</a></li>
            <?php if ($user->IsAdmin())
            { ?>
                <li><a href="adduser.php">Add User</a></li>
                <li><a href="moduser.php">Modify User</a></li>
            <?php } ?>
        </ul>

    </section>
    <footer>

        <ul>
            <li class="footerTab">
                <a onclick="showTools(this)">TOOLS</a>
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
