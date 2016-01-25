<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 04/10/14
 * Time: 18:02
 */
include_once "ConnettiDB.php";

class Accesso{

    private $db = NULL;
    private $sourceTab = "ACCESSI";

    function __construct($utente){
        $this->db = ConnettiDB::getConnection();
        $sql = $this->db->prepare("INSERT INTO $this->sourceTab(ID_UTENTE) VALUES(?)");
        $sql->execute(array($utente));
    }



} 