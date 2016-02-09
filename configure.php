<?php

session_start();
include_once dirname(__FILE__) . "/functions/functions.php";
include_once dirname(__FILE__) . "/classes/Application.php";


$application = NULL;
try
{
    $application = Application::getAppInfo();
} catch (Exception $e)
{
    $error = $e->getMessage();
}
try
{

    if (!$application == NULL)
    {
        if ($application->IsConfigured())
        {
            redirect("login.php", 301);
            exit;

        } else if (isset($_POST['USERNAME']) && isset($_POST['PWD']) && isset($_POST['PWDR']))
        {
            //TODO Clear Input
            if ($_POST['USERNAME'] == "" || $_POST['PWD'] == "" || $_POST['PWDR'] == "")
                throw new Exception("Fields cannot be empty");

            if ($_POST['PWD'] != $_POST['PWDR'])
                throw new Exception("Two passwords are different");

            $new = new User();
            $new->SetID($_POST['USERNAME']);
            $new->SetPassword($_POST['PWD']);
            $new->SetAdmin(TRUE);
            $new->SetValid(TRUE);
            $new->Save();
            $msg = "User added successfully";
        }
    }

} catch (Exception $e)
{
    $error = $e->getMessage();
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

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div class="page configure">

    <form id="configure_Form" class="configure" action="configure.php" method="POST" data-ajax="false">
        <p class="logo"></p>

        <?php
        if (isset($error))
            echo "<p class='error'>$error</p>";
        else if (isset($msg))
            echo "<p class='message'>$msg</p>";
        else
        {
            ?>

            <p class="info">
                It's the first time that you use this application, you have to configure it creating the first Admin
                Account
            </p>
            <p class="input">
                <label for="username">Username:</label>
                <input id="username" type="text" name="USERNAME" value=""
                       placeholder="Username" onkeypress="return submitOnEnter(event, 'configure_Form')"/>
                <label for="pwd1">Password:</label>
                <input id="pwd1" type="password" name="PWD" value=""
                       placeholder="Password" onkeypress="return submitOnEnter(event, 'configure_Form')"/>
                <label for="pwd2">Repeat Password:</label>
                <input id="pwd2" type="password" name="PWDR" value=""
                       placeholder="Repeat Password" onkeypress="return submitOnEnter(event, 'configure_Form')"/>
            </p>

            <p class="input button" id="configure" onclick="submitForm(this)">CONFIRM</p>

        <?php } ?>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="scripts/function.js"></script>
</body>
</html>