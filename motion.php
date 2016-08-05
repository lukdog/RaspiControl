<?php

session_start();
include_once dirname(__FILE__) . "/classes/User.php";
include_once dirname(__FILE__) . "/classes/Application.php";
include_once dirname(__FILE__) . "/functions/functions.php";

if (!isset($_SESSION['USERNAME']))
{
    redirect("login.php", 301);
} else
{
    //TODO control of session duration
    try
    {
        $user = new User($_SESSION['USERNAME']);
        $app = Application::getAppInfo();
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
      onload="alert('onload')">
<div class="page motion">

    <header>
        RaspiControl
    </header>
    <section class="motion mainSection">

        <div class="stream">
            <?php
            if (isset($error))
            {
                echo "<p class='error'>" . $error . "</p>";
            } else
            { ?>

                <?php
            }
            ?>
        </div>
        <a href="index.php">BACK</a>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="scripts/function.js"></script>
<script type="text/javascript">
    window.onload = function () {

        setFooterWidth();

        if ($("p.error").length == 0) {
            $("div.stream").append("<img src=''/>");
        }
    }

</script>
</body>
</html>
