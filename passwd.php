<?php

/*
 * Page that permits to an user to change his password
 */


include_once dirname(__FILE__) . "/classes/User.php";
include_once dirname(__FILE__) . "/functions/functions.php";
session_start();

if (!isset($_SESSION['USERNAME']))
{
    redirect("login.php", 301);
} else
{

    //TODO check Session Duration
    try
    {
        $user = new User($_SESSION['USERNAME']);

        //TODO Clear Input
        if (isset($_POST['OLDPWD']) && isset($_POST['PWD']) && isset($_POST['PWDR']))
        {
            if ($_POST['OLDPWD'] == "" || $_POST['PWD'] == "" || $_POST['PWDR'] == "")
                throw new Exception("Fields cannot be empty");

            try
            {
                if ($user->HasPassword($_POST['OLDPWD']))
                {
                    $user->ChangePassword($_POST['PWD'], $_POST['PWDR']);
                } else
                {
                    $error = "Wrong Password";
                    $_SESSION = array();
                    if (ini_get("session.use_cookies"))
                    {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 3600 * 24, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                    }
                    session_destroy();
                }
            } catch (Exception $e)
            {
                $error = $e->getMessage();
            }

            $msg = "Password Changed Successfully";
        }

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
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- Stylesheet -->
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
<div class="page passwd" data-role="page" data-theme="b">

    <header>
        RaspiControl
    </header>


    <form id="passwd_Form" class="passwd" action="passwd.php" method="POST" data-ajax="false">
        <?php
        if (isset($error))
            echo "<p class='error'>$error</p>";
        else if (isset($msg))
            echo "<p class='message'>$msg</p>";
        else
        {

            ?>
            <p class="input">
                <label for="oldpwd">Old Password:</label>
                <input id="oldpwd" type="password" name="OLDPWD" value=""
                       placeholder="Old Password" onkeypress="return submitOnEnter(event, 'passwd_Form')"/>
                <label for="pwd1">New Password:</label>
                <input id="pwd1" type="password" name="PWD" value=""
                       placeholder="Password" onkeypress="return submitOnEnter(event, 'passwd_Form')"/>
                <label for="pwd2">Repeat new Password:</label>
                <input id="pwd2" type="password" name="PWDR" value=""
                       placeholder="Repeat Password" onkeypress="return submitOnEnter(event, 'passwd_Form')"/>
            </p>
            <p class="input button" id="passwd" onclick="submitForm(this)">CHANGE</p>
        <?php } ?>
    </form>


    <footer>

        <ul>
            <li class="footerTab">
                <?php
                if (isset($error))
                    echo "<a href=\"passwd.php\">BACK</a>";
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