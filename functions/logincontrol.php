<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 04/10/14
 * Time: 15:09
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/control/classes/Utente.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/control/classes/Access.php";
session_start();

$user = $_POST['username'];
$pass = $_POST['password'];

echo "entro in logincontrol.php";
/*Controllo che Username e Password siano corrispondenti*/
$ctrl = login($user, $pass);

if ($ctrl)
{
    //Se l'accesso è effettuato correttamente allora creo nuova sessione...
    $accesso = new Accesso($user);
    $_SESSION['usernameLogin'] = $user;
    header("location:../index.php");
} else
{
    header("location:../login.php");
}


//Funzione che controlla se la password corrisponde all'username e se username è esistente e attivo
function login($username, $password)
{

    //Protezione da SQLInjection
    $username = stripslashes($username);
    $username = strtolower($username);
    $password = stripslashes($password);

    echo "Controllo Username e Password";

    //Chiamo Oggetto Utente con username
    $utente = new User($username);
    if ($utente->GetID() != $username)
        return FALSE;

    if (!$utente->IsAttivo())
    {
        echo "inattivo";
        return FALSE;
    }

    if ($utente->HaPassword($password))
    {
        echo "password OK";
        return TRUE;
    } else
    {
        return FALSE;
    }
}

?>