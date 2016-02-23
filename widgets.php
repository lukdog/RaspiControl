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
        $fs = $app->GetFS();
        if (count($fs) <= 0)
            throw new Exception("You have to configure widget in config file");
        $output = shell_exec("df");
        $o = explode("\n", $output);
        $used = 0;
        $avail = 0;
        $data = NULL;
        foreach ($o as $line)
        {
            $l = preg_replace("/ +/", " ", $line);
            $e = explode(" ", $l);
            if (isset($e[5]))
                if (isset($fs[$e[5]]))
                {
                    $data[] = array($fs[$e[5]], $e[2], $e[3]);
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
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" onresize="setFooterWidth(); drawChart()"
      onload="setFooterWidth()">
<div class="page widgets">

    <header>
        RaspiControl
    </header>

    <section class="widgets mainSection">


        <?php
        if (isset($error))
        {
            echo "<p class='error'>" . $error . "</p>";
        } else
        {
            if ($data != NULL)
                foreach ($data as $fs)
                {
                    $name = preg_replace("/ +/", "", $fs[0]);
                    echo "<div class=\"chart\"><p>Available space on $fs[0]</p>";
                    echo "<div class=\"pie\" id=\"free_$name\">please wait...</div>";
                    echo "Used: " . number_format($fs[1] / pow(2, 20), 1) . "GB - ";
                    echo "Free: " . number_format($fs[2] / pow(2, 20), 1) . "GB";
                    echo "</div>";
                }
        }
        ?>

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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

        var size = $('.chart').width();
        var baseP = 60;
        var maxReduction = 80;
        var perc = "60%";
        var p = 100 - baseP;
        if (size >= 400) {
            p = size * (100 - baseP) / 400;
            if (p > maxReduction) p = maxReduction;
            perc = (100 - p) + "%";
        }
        var s = size * (100 - p) / 100;

        var options = {
            fontName: 'Economica',
            fontSize: 25,
            backgroundColor: 'none',
            width: size,
            height: s,
            chartArea: {width: perc, height: '100%'},
            enableInteractivity: false,
            reverseCategories: true,
            legend: {position: 'none', textStyle: {color: '#9D9D9D'}},
            colors: ['#2b2b2b', '#d6264f']
        };


        <?php
        foreach ($data as $fs)
        {
            $name = preg_replace("/ +/", "", $fs[0]);
            echo "var data_$name = google.visualization.arrayToDataTable([['Type', 'Size'],";
            $used = $fs[1];
            $avail = $fs[2];
            echo "['used', $used],";
            echo "['free', $avail]]);";
            echo "var $name = new google.visualization.PieChart(document.getElementById('free_$name'));";
            echo "$name.draw(data_$name, options);";
        }

        ?>
    }
</script>
</body>
</html>
