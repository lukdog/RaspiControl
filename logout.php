<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 11/10/14
 * Time: 15:06
 */

include_once dirname(__FILE__) . "/functions/functions.php";

Logout();
header("location:login.php");