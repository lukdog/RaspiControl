<?php

/*
 * Page that Permits to an Admin to create a new User
 */

//TODO think about a new system to create users, password, etc..

include_once dirname(__FILE__) . "/classes/User.php";
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
            if (isset($_POST['USERNAME']) && isset($_POST['PWD']) && isset($_POST['PWDR']))
            {
                if ($_POST['USERNAME'] == "" || $_POST['PWD'] == "" || $_POST['PWDR'] == "")
                    throw new Exception("Fields cannot be empty");


                if ($_POST['PWD'] != $_POST['PWDR'])
                    throw new Exception("Two passwords are different");

                $username = clearInput($_POST['USERNAME']);
                $usernameN = strip_tags($username);
                if ($usernameN != $username)
                    throw new Exception("Inserted Username is not valid");

                $username = strtolower($username);

                $new = new User();
                $new->SetID($username);
                $new->SetPassword($_POST['PWD']);
                $new->SetAdmin(isset($_POST['ADMIN']));
                $new->SetValid(TRUE);
                $new->Save();
                $msg = "User added successfully";
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
<div class="page adduser">

    <header>
        RaspiControl
    </header>


    <form id="adduser_Form" class="adduser" action="adduser.php" method="POST" data-ajax="false">

        <?php
        if (isset($error))
            echo "<p class='error'>$error</p>";
        else if (isset($msg))
            echo "<p class='message'>$msg</p>";
        else
        {
            ?>

            <p class="input">
                <label for="username">Username:</label>
                <input id="username" type="text" name="USERNAME" value=""
                       placeholder="Username" onkeypress="return submitOnEnter(event, 'adduser_Form')"/>
                <label for="pwd1">Password:</label>
                <input id="pwd1" type="password" name="PWD" value=""
                       placeholder="Password" onkeypress="return submitOnEnter(event, 'adduser_Form')"/>
                <label for="pwd2">Repeat Password:</label>
                <input id="pwd2" type="password" name="PWDR" value=""
                       placeholder="Repeat Password" onkeypress="return submitOnEnter(event, 'adduser_Form')"/>
            </p>

            <div class="switch">
                <p>Admin:</p>
                <label class="off" for="check" onclick="selectBtn(this)">NO</label>
                <input type="checkbox" name="ADMIN" id="check">
            </div>

            <p class="input button" id="adduser" onclick="submitForm(this)">CREATE</p>

        <?php } ?>
    </form>

    <footer>
        <ul>
            <li class="footerTab">
                <?php
                if (isset($error) && $user->IsAdmin())
                    echo "<a href=\"adduser.php\">BACK</a>";
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