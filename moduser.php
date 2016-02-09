<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * User: Luca Doglione
 * Date: 05/10/14
 * Time: 12:17
 */
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

                $new = new User($_POST['USERNAME']);
                $new->SetAdmin(isset($_POST['ADMIN']));
                $new->SetValid(isset($_POST['ACTIVE']));
                $new->Save();
                $msg = "User modified successfully";
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
    <title>raspiControl</title>
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
<div class="page moduser">

    <header>
        RaspiControl
    </header>


    <form class="moduser" id="moduser_Form" action="moduser.php" method="POST" data-ajax="false">

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
                    <li class="select" onclick='$("ul.list").slideToggle("normal")'>
                        <input id="username" type="text" name="USERNAME" value=""
                               placeholder="Select User" onfocus="this.blur()" readonly/>
                        <span></span>
                    </li>
                </ul>
                <ul class="list">
                    <?php printUsers() ?>
                </ul>
            </section>
            <div class="switch">
                <p>Active:</p>
                <label class="on" for="checkActive" onclick="selectBtn(this)">YES</label>
                <input type="checkbox" name="ACTIVE" id="checkActive" checked>
            </div>
            <div class="switch">
                <p>Admin:</p>
                <label class="off" for="checkAdmin" onclick="selectBtn(this)">NO</label>
                <input type="checkbox" name="ADMIN" id="checkAdmin">
            </div>
            <p class="input button" id="moduser" onclick="submitForm(this)">MODIFY</p>

        <?php } ?>

    </form>


    <footer>
        <ul>
            <li class="footerTab">
                <?php
                if (isset($error) && $user->IsAdmin())
                    echo "<a href=\"moduser.php\">BACK</a>";
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