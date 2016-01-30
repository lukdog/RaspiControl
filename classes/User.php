<?php

include_once dirname(__FILE__) . "/DBConnection.php";

/*
 * Class User
 * define Object User relative to table USERS in DB
 * this class contains info about users and methods to create a new user
 */

class User
{

    protected $nid = NULL;
    protected $id = NULL;
    protected $password = NULL;
    protected $salt = NULL;
    protected $valid = 0;
    protected $admin = 0;
    private $new = FALSE;
    private $modified = FALSE;
    private $db = NULL;
    private $sourceTab = "USERS";


    //Costruttore della classe Utente
    public function __construct($id = NULL)
    {

        $this->db = DBConnection::getConnection();
        if ($id == NULL)
        {
            $this->new = TRUE;
        } else
        {
            $id = $this->db->quote($id);
            $query = "SELECT * FROM $this->sourceTab WHERE ID=$id";
            try
            {
                $res = $this->db->query($query);
                if ($res->rowCount() != 1)
                    $this->valid = FALSE;
                else
                {
                    $info = $res->fetch(PDO::FETCH_ASSOC);
                    $this->nid = $info['NID'];
                    $this->id = $info['ID'];
                    $this->password = $info["PASSWORD"];
                    $this->salt = $info["SALT"];
                    $this->valid = $info["VALID"];
                    $this->admin = $info["ADMIN"];

                }
            } catch (Exception $e)
            {
                throw new Exception("Impossible to Execute Query that retrieve an user: " . $e->getMessage());
            }
        }
    }

    public function SetID($id)
    {
        if (strlen($id) > 20)
            throw new Exception("Error: username can be at least of 20 chars");
        $this->id = $id;
        $this->modified = TRUE;
    }

    public function SetPassword($password)
    {
        //TODO better way to store password
        $this->password = md5($password);
        $this->modified = TRUE;
    }

    public function SetValid($at)
    {
        if ($at == "TRUE")
            $this->valid = 1;
        else
            $this->valid = 0;
        $this->modified = TRUE;
    }

    public function SetAdmin($ad)
    {
        if ($ad == "TRUE")
            $this->admin = 1;
        else
            $this->admin = 0;
        $this->modified = TRUE;
        echo $this->admin;
    }

    public function GetID()
    {
        return $this->id;
    }

    public function IsValid()
    {
        if ($this->valid == 1)
            return TRUE;
        else
            return FALSE;
    }

    public function IsAdmin()
    {
        if ($this->admin == 1)
            return TRUE;
        else
            return FALSE;
    }

    public function HasPassword($psw)
    {
        //TODO implement salted password check
        if ($this->password == md5($psw))
            return TRUE;
        else
            return FALSE;
    }

    public function Save()
    {

        if ($this->modified and !$this->new)
        {
            $query = "UPDATE $this->sourceTab SET PASSWORD=?, VALID=?, ADMIN=? WHERE ID=? ";
            try
            {
                $sql = $this->db->prepare($query);
            } catch (Exception $e)
            {
                throw new Exception("Impossible to Update " . $this->id . " user");
            }
            $data = array($this->password, $this->valid, $this->admin, $this->id);
            //var_dump($data);

        } else if ($this->new)
        {
            if ($this->id == NULL or $this->password == NULL or $this->valid == NULL)
            {
                throw new Exception("Some user's attribute are not specified");
            }
            $query = "INSERT INTO $this->sourceTab(ID, PASSWORD, VALID) VALUES(?, ?, ?)";
            try
            {
                $sql = $this->db->prepare($query);
            } catch (Exception $e)
            {
                throw new Exception("Impossible to insert " . $this->id . " user");
            }
            $data = array($this->id, $this->password, $this->valid);
        } else return FALSE;

        try
        {
            $this->db->beginTransaction();
        } catch (Exception $e)
        {
            throw new Exception("Impossible to securely manage users table");
        }

        try
        {
            $sql->execute($data);
            $this->db->commit();
            $this->new = FALSE;
            $this->modified = FALSE;
            return TRUE;
        } catch (Exception $e)
        {
            $this->db->rollBack();
            throw new Exception("Impossible to correctly conclude the operation");
        }
    }

} 