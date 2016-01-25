<?php
/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 05/10/14
 * Time: 11:15
 */

include_once "ConnettiDB.php";

class Script{

    private $id = NULL;
    private $nome = NULL;
    private $testo = NULL;
    private $categoria = NULL;
    private $nuovo = FALSE;
    private $modificato = FALSE;
    private $sourceTab = "SCRIPTS";
    private $db = NULL;

    //Costruttore che permette di richiamare uno script esistente o di crearne uno nuovo
    public function __construct($id=NULL){

        if($id==NULL){
            $nuovo = TRUE;
        } else {
            $this->db = ConnettiDB::getConnection();
            $sql = "SELECT * FROM $this->sourceTab WHERE ID=$id";

            foreach($this->db->query($sql) as $temp){
                $this->id = $temp['ID'];
                $this->nome = $temp['NOME'];
                $this->testo = $temp['TESTO'];
                $this->categoria = $temp['CATEGORIA'];
            }
        }


    }


    /*Metodi di Get per ottenere informazioni da script presente nel database*/

    public function GetId(){
        return $this->id;
    }

    public function GetNome(){
        return $this->nome;
    }

    public function GetTesto(){
        return $this->testo;
    }

    public function GetCategoria(){
        return $this->categoria;
    }

    /*Metodi di Set per settare i valori di uno script nuovo*/
    public function SetNome($nome){
        $this->nome = $nome;
        $this->modificato = TRUE;
    }

    public function SetTesto($testo){
        $this->testo = $testo;
        $this->modificato = TRUE;
    }

    public function SetCategoria($categoria){
        $this->categoria = $categoria;
        $this->modificato = TRUE;
    }

    public function SetAll($nome, $testo, $categoria){
        $this->nome = $nome;
        $this->testo = $testo;
        $this->categoria = $categoria;
        $this->modificato = TRUE;
    }

    //Metodo per Eseguire lo script sul Server
    public function Esegui(){
        $output = shell_exec($this->testo);
        return $output;
    }

    //Metodo per Salvare uno Script modificato o Creato
    public function Salva(){
        if($this->nuovo){
            $this->db = ConnettiDB::getConnection();
            $this->db->beginTransaction();
            if($this->nome == NULL or $this->testo == NULL or $this->categoria == NULL){
                echo "WARNING: utilizzare metodi SET";
                return FALSE;
            }
            try{
                $sql = $this->db->prepare("INSERT INTO $this->SourceTab(NOME, TESTO, CATEGORIA) VALUES(?,?,?)");
                $sql->execute(array($this->nome, $this->testo, $this->categoria));
                $this->db->commit();
                $this->nuovo = FALSE;
                $this->modificato = FALSE;
                return TRUE;
            } catch (Exception $e){
                echo $e->getMessage();
                $this->db->rollback();
                return FALSE;
            }
        } else{
            if($this->modificato)
            {
                $this->db = ConnettiDB::getConnection();
                $this->db->beginTransaction();
                if($this->nome == NULL or $this->testo == NULL or $this->categoria == NULL){
                    echo "WARNING: utilizzare metodi SET";
                    return FALSE;
                }
                try{
                    $upd="UPDATE $this->sourceTab SET NOME=?, TESTO=?, CATEGORIA=? WHERE ID=?";
                    $sql = $this->db->prepare($upd);
                    $sql->execute(array($this->nome, $this->testo, $this->categoria, $this->id));
                    $this->db->commit();
                    $this->modificato = FALSE;
                    return TRUE;
                } catch (Exception $e){
                    echo $e->getMessage();
                    $this->db->rollback();
                    return FALSE;
                }
            }
        }
    }


} 