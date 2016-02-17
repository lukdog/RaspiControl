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

    public function SetValid($at)
    {
        if ($at == TRUE)
            $this->valid = 1;
        else
            $this->valid = 0;
        $this->modified = TRUE;
    }

    public function SetAdmin($ad)
    {
        if ($ad == true)
            $this->admin = 1;
        else
            $this->admin = 0;
        $this->modified = TRUE;
    }

    public function GetID()
    {
        return $this->id;
    }

    public function SetID($id)
    {
        if (strlen($id) > 20)
            throw new Exception("Error: username can be at least of 20 chars");
        $this->id = $id;
        $this->modified = TRUE;
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
        if ($this->salt == NULL)
        {
            if ($this->password == md5($psw))
                return TRUE;
        } else
        {
            if ($this->password == md5($psw . $this->salt))
                return TRUE;
        }
        return FALSE;
    }

    public function ChangePassword($new, $newR)
    {
        if ($new != $newR)
        {
            throw new Exception("Two passwords are not equal");
        }

        $this->SetPassword($new);
        $this->Save();

    }

    public function SetPassword($password)
    {
        $this->salt = md5(mt_rand());
        $this->password = md5($password . $this->salt);
        $this->modified = TRUE;
    }

    public function Save()
    {

        if ($this->modified and !$this->new)
        {
            $query = "UPDATE $this->sourceTab SET PASSWORD=?, VALID=?, ADMIN=?, SALT=? WHERE ID=? ";
            try
            {
                $sql = $this->db->prepare($query);
            } catch (Exception $e)
            {
                throw new Exception("Impossible to Update " . $this->id . " user");
            }
            $data = array($this->password, $this->valid, $this->admin, $this->salt, $this->id);
            //var_dump($data);

        } else if ($this->new)
        {
            if ($this->id == NULL or $this->password == NULL or $this->valid == NULL or $this->admin == NULL)
            {
                throw new Exception("Some user's attribute are not specified");
            }
            $query = "INSERT INTO $this->sourceTab(ID, PASSWORD, VALID, ADMIN, SALT) VALUES(?, ?, ?, ?, ?)";
            try
            {
                $sql = $this->db->prepare($query);
            } catch (Exception $e)
            {
                throw new Exception("Impossible to insert " . $this->id . " user");
            }
            $data = array($this->id, $this->password, $this->valid, $this->admin, $this->salt);
        } else return FALSE;

        try
        {
            $this->db->beginTransaction();
        } catch (Exception $e)
        {
            throw new Exception("Impossible to securely manage users table");
        }

        if ($this->new)
        {
            try
            {
                $newid = $this->db->quote($this->id);
                $query = "SELECT ID FROM $this->sourceTab WHERE ID=$newid FOR UPDATE";
                $res = $this->db->query($query);
                if ($res->rowCount() != 0)
                    throw new Exception();
            } catch (Exception $e)
            {
                $this->db->rollBack();
                throw new Exception("Username is not valid, it's already in use");
            }
        }


        try
        {
            if ($sql->execute($data))
            {
                $this->db->commit();
                $this->new = FALSE;
                $this->modified = FALSE;
                return TRUE;
            } else throw new Exception();
        } catch (Exception $e)
        {
            $this->db->rollBack();
            throw new Exception("Impossible to correctly conclude the operation");
        }
    }

} 