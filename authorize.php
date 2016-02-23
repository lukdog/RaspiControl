<?php

include_once dirname(__FILE__) . "/classes/Script.php";
include_once dirname(__FILE__) . "/functions/functions.php";
session_start();

if (!isset($_SESSION['USERNAME']))
{
    redirect("login.php", 301);
} else
{
    //TODO check session duration
    try
    {
        $user = new User($_SESSION['USERNAME']);
        if (!$user->IsAdmin())
        {
            //TODO Reporting through logger
            throw new Exception("You have not admin permissions, this abuse will be reported");
        } else
        {
            if (isset($_POST['USERNAME']))
            {
                if ($_POST['USERNAME'] == "")
                    throw new Exception("You Have to Select an Username");

                if ($_POST['SCRIPT'] == "")
                    throw new Exception("You Have to Select an Username");

                $username = clearInput($_POST['USERNAME']);
                $scriptId = clearInput($_POST['SCRIPT']);
                $usernameN = strip_tags($username);
                if ($usernameN != $username)
                    throw new Exception("Inserted Username is not valid");

                if (!is_numeric($scriptId))
                    throw new Exception("Inserted Script id is not valid");

                $username = strtolower($username);

                $u = new User($username);
                $script = new Script($scriptId);
                $u->authorize($script);
                $msg = "User successfully authorized";
            }
        }
    } catch (Exception $e)
    {
        $error = $e->getMessage();
    }

}
?>


<html>
<head>
    <title>RaspiControl</title>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi"/>
    <meta name="msapplication-tap-highlight" content="no"/>
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Stylesheet-->
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

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" onresize="setFooterWidth()"
      onload="setFooterWidth()">
<div class="page authorize">

    <header>
        RaspiControl
    </header>


    <form class="authorize" id="authorize_Form" action="authorize.php" method="POST" data-ajax="false">

        <?php
        if (isset($error))
            echo "<p class='error'>$error</p>";
        else if (isset($msg))
            echo "<p class='message'>$msg</p>";
        else
        {
            ?>
            <section class="selector">
                <ul>
                    <li class="select" onclick='$("#listUser").slideToggle("normal")'>
                        <input id="username" type="text" name="USERNAME" value=""
                               placeholder="Select User" onfocus="this.blur()" readonly/>
                        <span></span>
                    </li>
                </ul>
                <ul class="list" id="listUser">
                    <?php printUsers("username", "listUser") ?>
                </ul>
            </section>
            <section class="selector">
                <ul>
                    <li class="select" onclick='$("#listScript").slideToggle("normal")'>
                        <input id="script" type="text" name="SCRIPT" value=""
                               placeholder="Select Script ID" onfocus="this.blur()" readonly/>
                        <span></span>
                    </li>
                </ul>
                <ul class="list" id="listScript">
                    <?php printScripts("script", "listScript") ?>
                </ul>
            </section>
            <p class="input button" id="authorize" onclick="submitForm(this)">AUTHORIZE</p>

        <?php } ?>

    </form>


    <footer>
        <ul>
            <li class="footerTab">
                <?php
                if (isset($error) && $user->IsAdmin())
                    echo "<a href=\"authorize.php\">BACK</a>";
                else echo "<a href=\"index.php\">BACK</a>";
                ?>
            </li>
            <li class="footerTab">
                <a href="index.php?LOGOUT">LOGOUT</a>
            </li>
        </ul>
    </footer>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="scripts/function.js"></script>
</body>
</html>