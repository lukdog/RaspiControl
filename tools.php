<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 11/03/15
 * Time: 12:00
 */
session_start();
include_once "./classes/Utente.php";
include_once "./classes/Script.php";
include_once "./scripts/function.php";

//Controllo che la sessione sia registrata e recupero l'utente che ha fatto il LOGIN
if(!isset($_SESSION['usernameLogin'])){
    echo "Sessione non registrata";
    header("location:login.php");
} else{
    $utente = new Utente($_SESSION['usernameLogin']);
    echo $utente->GetID();
    if($utente->IsAdmin())
        echo "ADMIN";
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
    <link rel="stylesheet" href="style/style.css" />
    <!--Google Fonts !-->
    <link href='http://fonts.googleapis.com/css?family=Economica:400,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="images/favicon.ico" >
    <link rel="icon" href="images/icon.png" type="image/png" />
    <link rel="apple-touch-icon" href="images/icon.png" type="image/png" />
    <link rel="mask-icon" color="#d6264f" href="images/iconP.svg">

</head>

<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0">
<div class="pagina paginamenu" data-role="page" data-theme="b">

    <div data-role="header">
        <h1>Control</h1>
    </div>

    <div data-role="main" id="loginContent" class="ui-content">
        <form class="menu" action="./scripts/whichtool.php" method="POST" data-ajax="false">

            <button type='submit' value='modpasswd' name='tools' class='ui-shadow ui-btn ui-corner-all ui-icon-home'>
                Cambia Password
            </button>

            <?php
                if($utente->IsAdmin()){
                    echo "<button type='submit' value='moduser' name='tools' class='ui-shadow ui-btn ui-corner-all ui-icon-home'>"
                    . "Modifica Utente </button>";

                    echo "<button type='submit' value='newuser' name='tools' class='ui-shadow ui-btn ui-corner-all ui-icon-home'>"
                        . "Aggiungi Utente </button>";

                }
            ?>

        </form>
    </div>
    <div data-role="footer">
        <div data-role="navbar">
            <ul>
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</body>
</html>
