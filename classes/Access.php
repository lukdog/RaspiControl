<?php

include_once dirname(__FILE__) . "/DBConnection.php";

/*
 * Class Access
 * Define the Object Access relative to table ACCESS in DB
 * Access table contains info relative to users' access to web application
 */

class Access
{

    private $db = NULL;
    private $sourceTab = "ACCESSI";

    function __construct($user)
    {
        $this->db = DBConnection::getConnection();
        $query = "INSERT INTO $this->sourceTab(ID_UTENTE) VALUES(?)";

        $sql = $this->db->prepare($query);
        $sql->execute(array($user));
    }


}