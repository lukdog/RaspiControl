<?php

include_once dirname(__FILE__) . "/DBConnection.php";


/*
 * Class Script
 * define Object Script relative to table SCRIPTS in DB
 * Scripts table contains scripts that can be executed from the application on raspberry
 */

class Script
{

    private $id = NULL;
    private $nome = NULL;
    private $testo = NULL;
    private $categoria = NULL;
    private $nuovo = FALSE;
    private $modificato = FALSE;
    private $sourceTab = "SCRIPTS";
    private $db = NULL;

    /*
     * Constructor that permits to retrieve an existent script from DB
     * or to instantiate a new Script that have to be stored
     */
    public function __construct($id = NULL)
    {

        if ($id == NULL)
        {
            $nuovo = TRUE;
        } else
        {
            $this->db = DBConnection::getConnection();
            $sql = "SELECT * FROM $this->sourceTab WHERE ID=$id";

            foreach ($this->db->query($sql) as $temp)
            {
                $this->id = $temp['ID'];
                $this->nome = $temp['NOME'];
                $this->testo = $temp['TESTO'];
                $this->categoria = $temp['CATEGORIA'];
            }
        }


    }


    /*Metodi di Get per ottenere informazioni da script presente nel database*/

    public function GetId()
    {
        return $this->id;
    }

    public function GetNome()
    {
        return $this->nome;
    }

    public function GetTesto()
    {
        return $this->testo;
    }

    public function GetCategoria()
    {
        return $this->categoria;
    }

    /*Metodi di Set per settare i valori di uno script nuovo*/
    public function SetNome($nome)
    {
        $this->nome = $nome;
        $this->modificato = TRUE;
    }

    public function SetTesto($testo)
    {
        $this->testo = $testo;
        $this->modificato = TRUE;
    }

    public function SetCategoria($categoria)
    {
        $this->categoria = $categoria;
        $this->modificato = TRUE;
    }

    public function SetAll($nome, $testo, $categoria)
    {
        $this->nome = $nome;
        $this->testo = $testo;
        $this->categoria = $categoria;
        $this->modificato = TRUE;
    }

    //Metodo per Eseguire lo script sul Server
    public function Esegui()
    {
        $output = shell_exec($this->testo);
        return $output;
    }

    //Metodo per Salvare uno Script modificato o Creato
    public function Salva()
    {
        if ($this->nuovo)
        {
            $this->db = DBConnection::getConnection();
            $this->db->beginTransaction();
            if ($this->nome == NULL or $this->testo == NULL or $this->categoria == NULL)
            {
                echo "WARNING: utilizzare metodi SET";
                return FALSE;
            }
            try
            {
                $sql = $this->db->prepare("INSERT INTO $this->SourceTab(NOME, TESTO, CATEGORIA) VALUES(?,?,?)");
                $sql->execute(array($this->nome, $this->testo, $this->categoria));
                $this->db->commit();
                $this->nuovo = FALSE;
                $this->modificato = FALSE;
                return TRUE;
            } catch (Exception $e)
            {
                echo $e->getMessage();
                $this->db->rollback();
                return FALSE;
            }
        } else
        {
            if ($this->modificato)
            {
                $this->db = DBConnection::getConnection();
                $this->db->beginTransaction();
                if ($this->nome == NULL or $this->testo == NULL or $this->categoria == NULL)
                {
                    echo "WARNING: utilizzare metodi SET";
                    return FALSE;
                }
                try
                {
                    $upd = "UPDATE $this->sourceTab SET NOME=?, TESTO=?, CATEGORIA=? WHERE ID=?";
                    $sql = $this->db->prepare($upd);
                    $sql->execute(array($this->nome, $this->testo, $this->categoria, $this->id));
                    $this->db->commit();
                    $this->modificato = FALSE;
                    return TRUE;
                } catch (Exception $e)
                {
                    echo $e->getMessage();
                    $this->db->rollback();
                    return FALSE;
                }
            }
        }
    }


} 