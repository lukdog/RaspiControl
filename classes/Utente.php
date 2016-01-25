<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 04/10/14
 * Time: 11:44
 */

include_once "ConnettiDB.php";

class Utente{

    protected $nid = NULL;
    protected $id = NULL;
    protected $password = NULL;
    protected $attivo = 0;
    protected $admin = 0;
    private $nuovo = FALSE;
    private $modificato = FALSE;
    private $db = NULL;
    private $sourceTab = "UTENTI";


    //Costruttore della classe Utente
    public function __construct($id = NULL){

        $this->db = ConnettiDB::getConnection();
        if($id == NULL)
        {
            $this->nuovo = TRUE;
        } else
        {
            //TODO MIgliorare eseuczione query poco sicura
            foreach($this->db->query("SELECT * FROM $this->sourceTab WHERE ID='$id'") as $temp)
            {
                $this->nid = $temp["NID"];
                $this->id= $temp["ID"];
                $this->password = $temp["PASSWORD"];
                $this->attivo = $temp["ATTIVO"];
                $this->admin = $temp["ADMIN"];
                if($id != $this->id){
                    //TODO migliorare gestione untente non esistente magari con un COUNT(*);
                    $this->attivo = 0;
                }
            }
        }
    }

    public function SetID($id){
        $this->id = $id;
        $this->modificato = TRUE;
    }

    public function SetPassword($password){
        $this->password = md5($password);
        $this->modificato = TRUE;
    }

    public function SetAttivo($at){
        if($at == "TRUE")
            $this->attivo = 1;
        else
            $this->attivo = 0;
        $this->modificato = TRUE;
    }

    public function SetAdmin($ad){
        if($ad == "TRUE")
            $this->admin = 1;
        else
            $this->admin = 0;
        $this->modificato = TRUE;
        echo $this->admin;
    }

    public function GetID(){
        return $this->id;
    }

    public function IsAttivo(){
        if($this->attivo == 1)
            return TRUE;
        else
            return FALSE;
    }

    public function IsAdmin(){
        if($this->admin == 1)
            return TRUE;
        else
            return FALSE;
    }

    public function HaPassword($psw){
        if($this->password == md5($psw))
            return TRUE;
        else
            return FALSE;
    }

    //Funzione per salvataggio
    public function Save(){

        if($this->modificato and !$this->nuovo){
            $query = "UPDATE $this->sourceTab SET PASSWORD=?, ATTIVO=?, ADMIN=? WHERE ID=? ";
            try{$sql = $this->db->prepare($query);}
            catch(Exception $e) {echo $e->getMessage(); return FALSE;}
            $data = array($this->password, $this->attivo, $this->admin, $this->id);
            var_dump($data);

        }else if($this->nuovo){
            if($this->id == NULL or $this->password == NULL or $this->attivo == NULL){
                echo "WARNING: utilizzare metodi SET";
                return FALSE;
            }
            $query = "INSERT INTO $this->sourceTab(ID, PASSWORD, ATTIVO) VALUES(?, ?, ?)";
            try{$sql = $this->db->prepare($query);}
            catch(Exception $e) {echo $e->getMessage(); return FALSE;}
            $data = array($this->id, $this->password, $this->attivo);
        } else return FALSE;

        try{$this->db->beginTransaction();} catch(Exception $e) {echo $e->getMessage(); return FALSE;}

        try{
            $sql->execute($data);
            $this->db->commit();
            $this->nuovo = FALSE;
            $this->modificato = FALSE;
            return TRUE;
        } catch(Exception $e){
            $this->db->rollBack();
            echo $e->getMessage();
            return FALSE;
        }
    }

} 