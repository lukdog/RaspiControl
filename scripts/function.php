<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 05/10/14
 * Time: 14:58
 */

include_once $_SERVER['DOCUMENT_ROOT'] . "/control/classes/ConnettiDB.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/control/classes/Utente.php";

//Funzione che crea il form
function CreaForm(Utente $utente){
    $nomeutente = $utente->getID();
    $categorie = ElencoCategorie();
    $db = ConnettiDB::getConnection();
    if(count($categorie)==0){
        echo "Nessuna Categoria Presente";
    } else{
        foreach($categorie as $temp){
            echo "<fieldset data-role='controlgroup' data-corners='false'>";
            echo "<legend>" . $temp . "</legend>";
            echo "<hr>";

            $sql = "SELECT ID, NOME FROM SCRIPTS WHERE CATEGORIA = '$temp' AND ID IN (SELECT ID_SCRIPT FROM PERMESSI WHERE ID_UTENTE='$nomeutente')";
            
            foreach($db->query($sql) as $script){
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
function ElencoCategorie(){
    $sql = "SELECT * FROM CATEGORIE";
    $db = ConnettiDB::getConnection();
    $count = 0;
    $categorie = NULL;
    foreach($db->query($sql) as $tmp){
        $categorie[$count] = $tmp['CATEGORIA'];
        $count++;
    }
    return $categorie;
}

//Funzione che effettua il logout
function Logout(){
    $_SESSION = array();
    session_unset();
    session_destroy();
}

//Funzione che inserisce un nuovo utente
function addUser($username, $password){
    $usr = new Utente();
    $usr->SetID($username);
    $usr->SetPassword($password);
    $usr->SetAttivo(TRUE);
    $usr->SetAdmin(FALSE);
    $ctrl = $usr->Save();
    return $ctrl;
}

//Funzione che modifica un utente già esistente
function modUser($username, $password=NULL, $attivo=NULL, $admin=NULL){
    $usr = new Utente($username);
    //echo $admin;
    if($usr->GetID() == NULL)
    {
        echo "Utente Non esistente";
        return FALSE;
    }

    if($password != NULL)
        $usr->SetPassword($password);

    if($attivo != NULL)
        $usr->SetAttivo($attivo);

    if($admin != NULL)
        $usr->SetAdmin($admin);

    if($password != NULL or $attivo != NULL or $admin != NULL){
        $ctrl = $usr->Save();
        return $ctrl;
    }
    return FALSE;
}

function userSelect(){
    $sql = "SELECT ID FROM UTENTI";
    $db = ConnettiDB::getConnection();

    echo "<label for='select-choice-1'>Scegli Utente:</label>";
    echo "<select class='scegliUser' name='username' id='select-choice-1'>";

    foreach($db->query($sql) as $tmp){
        $nome = $tmp["ID"];
        echo "<option value='$nome'>$nome</option>";
    }
    echo "</select>";
}

?>