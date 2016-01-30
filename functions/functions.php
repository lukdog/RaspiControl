<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 05/10/14
 * Time: 14:58
 */

include_once dirname(__FILE__) . "/../classes/DBConnection.php";
include_once dirname(__FILE__) . "/../classes/User.php";


/*
 * Function that check if parameters correspond to an existent and active user in DB
 */
function checklogin($username, $password)
{

    $username = trim($username);
    $usernameN = strip_tags($username);

    if ($usernameN != $username)
        throw new Exception("Inserted Username is not valid");

    $username = strtolower($username);
    $password = clearInput($password);

    if ($username == "" || $password == "")
        throw new Exception("Username and Password cannot be empty");

    if (strlen($username) > 20)
        throw new Exception("Username cannot be longer then 20 chars");

    $utente = new User($username);

    if (!$utente->IsValid())
        throw new Exception("User is not valid or it's not active");

    if ($utente->HasPassword($password))
        return TRUE;
    else
        throw new Exception("Invalid Password");

}


//Funzione che crea il form
function CreaForm(User $utente)
{
    $nomeutente = $utente->getID();
    $categorie = ElencoCategorie();
    $db = DBConnection::getConnection();
    if (count($categorie) == 0)
    {
        throw new Exception("There are no available categories in DB");
    } else
    {
        foreach ($categorie as $temp)
        {
            echo "<fieldset data-role='controlgroup' data-corners='false'>";
            echo "<legend>" . $temp . "</legend>";
            echo "<hr>";

            $sql = "SELECT ID, NOME FROM SCRIPTS WHERE CATEGORIA = '$temp' AND ID IN (SELECT ID_SCRIPT FROM PERMESSI WHERE ID_UTENTE='$nomeutente')";

            foreach ($db->query($sql) as $script)
            {
                $nomescript = $script["NOME"];
                $idscript = $script["ID"];
                $riga = "<button type='submit' value='$idscript' name='script' class='ui-shadow ui-btn ui-corner-all ui-icon-home'>" . $nomescript . "</button>";
                echo $riga;
            }
            echo "</fieldset>";
        }
    }
}

//Funzione che crea un array contenente tutte le categorie
function ElencoCategorie()
{
    $sql = "SELECT * FROM CATEGORIES";
    $db = DBConnection::getConnection();
    $count = 0;
    $categorie = NULL;
    foreach ($db->query($sql) as $tmp)
    {
        $categorie[$count] = $tmp['CATEGORIA'];
        $count++;
    }
    return $categorie;
}

//Funzione che effettua il logout
function Logout()
{
    $_SESSION = array();
    session_unset();
    session_destroy();
}

//Funzione che inserisce un nuovo utente
function addUser($username, $password)
{
    $usr = new User();
    $usr->SetID($username);
    $usr->SetPassword($password);
    $usr->SetValid(TRUE);
    $usr->SetAdmin(FALSE);
    $ctrl = $usr->Save();
    return $ctrl;
}

//Funzione che modifica un utente già esistente
function modUser($username, $password = NULL, $attivo = NULL, $admin = NULL)
{
    $usr = new User($username);
    //echo $admin;
    if ($usr->GetID() == NULL)
    {
        echo "Utente Non esistente";
        return FALSE;
    }

    if ($password != NULL)
        $usr->SetPassword($password);

    if ($attivo != NULL)
        $usr->SetValid($attivo);

    if ($admin != NULL)
        $usr->SetAdmin($admin);

    if ($password != NULL or $attivo != NULL or $admin != NULL)
    {
        $ctrl = $usr->Save();
        return $ctrl;
    }
    return FALSE;
}

function userSelect()
{
    $sql = "SELECT ID FROM UTENTI";
    $db = ConnettiDB::getConnection();

    echo "<label for='select-choice-1'>Scegli Utente:</label>";
    echo "<select class='scegliUser' name='username' id='select-choice-1'>";

    foreach ($db->query($sql) as $tmp)
    {
        $nome = $tmp["ID"];
        echo "<option value='$nome'>$nome</option>";
    }
    echo "</select>";
}

/*
 * Redirect to a new Page in a correct way
 */
function redirect($url, $code)
{
    if ($code == 301)
        header("HTTP/1.1 $code Moved Permanently");
    else if ($code == 302)
        header("HTTP/1.1 $code Moved Temporary");

    header("Location: $url");


}

/*
 * Clear Input
 */
function clearInput($str)
{
    $str = trim($str);
    $str = htmlentities($str);
    return $str;
}

?>