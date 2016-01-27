<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 11/03/15
 * Time: 12:27
 */


//Redirezione alla pagina scelta

$page = $_POST['tools'];

if ($page == NULL)
    header("location:../tools.php");

if ($page == "newuser")
    header("location:../newuserpage.php");

if ($page == "moduser")
    header("location:../moduserpage.php");

if ($page == "modpasswd")
    header("location:../modpasswdpage.php");

//header("location:../tools.php");