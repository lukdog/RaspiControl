<?php

include_once dirname(__FILE__) . "/DBConnection.php";
include_once dirname(__FILE__) . "/User.php";
include_once dirname(__FILE__) . "/Application.php";

/*
 * Class Script
 * define Object Script relative to table SCRIPTS in DB
 * Scripts table contains scripts that can be executed from the application on raspberry
 */

class Script
{

    private $id = NULL;
    private $name = NULL;
    private $cmd = NULL;
    private $type = NULL;
    private $category = NULL;
    private $alert = NULL;
    private $new = FALSE;
    private $modified = FALSE;
    private $sourceTab = "SCRIPTS";
    private $db = NULL;

    /*
     * Constructor that permits to retrieve an existent script from DB
     * or to instantiate a new Script that have to be stored
     */
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
                    throw new Exception("Script " . $id . " do not exists in DB");
                else
                {
                    $info = $res->fetch(PDO::FETCH_ASSOC);
                    $this->id = $info['ID'];
                    $this->name = $info["NAME"];
                    $this->cmd = $info["CMD"];
                    $this->type = $info["TYPE"];
                    $this->category = $info["CATEGORY"];
                    $this->alert = $info["ALERT"];

                }
            } catch (Exception $e)
            {
                throw new Exception("Impossible to Execute Query that retrieve a script " . $e->getMessage());
            }
        }


    }


    /*Metodi di Get per ottenere informazioni da script presente nel database*/

    public function GetId()
    {
        return $this->id;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function GetCmd()
    {
        return $this->cmd;
    }

    public function GetCategory()
    {
        return $this->category;
    }

    public function GetAlert()
    {
        return $this->alert;
    }

    public function SetName($name)
    {
        $this->name = $name;
        $this->modified = TRUE;
    }

    public function SetCmd($cmd)
    {
        $this->cmd = $cmd;
        $this->modified = TRUE;
    }

    public function SetCategory($category)
    {
        $this->category = $category;
        $this->modified = TRUE;
    }

    public function SetAll($name, $cmd, $category)
    {
        $this->name = $name;
        $this->cmd = $cmd;
        $this->category = $category;
        $this->modified = TRUE;
    }

    public function Exec(User $user)
    {

        $idu = $this->db->quote($user->GetID());
        $ids = $this->db->quote($this->id);
        $sql = "SELECT ID_USER FROM AUTHORIZATIONS WHERE ID_USER=$idu AND ID_SCRIPT=$ids";
        $res = NULL;
        try
        {
            $res = $this->db->query($sql);
        } catch (Exception $e)
        {
            throw new Exception("Impossible to execute command, retry later");
        }

        if ($res->rowCount() == 1)
        {
            try
            {

                $dir = Application::getAppInfo()->GetOutputDir();
                $cmd = NULL;

                if ($this->type == 1)
                {
                    $cmd = "touch $dir/$this->cmd";
                } else if ($this->type == 2)
                {
                    $cmd = "rm -f $dir/$this->cmd";
                } else if ($this->type == 3)
                {
                    $cmd = "echo \" \" >> $dir/$this->cmd";
                } else throw new Exception("Invalid type for script with id: $this->id");

                if (shell_exec($cmd) != NULL)
                    throw new Exception("Impossible to Execute this action now");

                sleep(2);
                $output = shell_exec('tail -n1 /tmp/.raspicontrol/.output');
                return $output;
            } catch (Exception $e)
            {
                throw new Exception("It's not possible to execute command, retry later");
            }
        } else
        {
            throw new Exception("You don't have permission to execute this command");
        }
    }

    public function Save()
    {
        if ($this->new)
        {

            if ($this->name == NULL or $this->cmd == NULL or $this->category == NULL)
            {
                throw new Exception("Some fields are not set");
            }
            try
            {
                $this->db->beginTransaction();
                $sql = $this->db->prepare("INSERT INTO $this->SourceTab(NAME, CMD, CATEGORY) VALUES(?,?,?)");
                $sql->execute(array($this->name, $this->cmd, $this->category));
                $this->db->commit();
                $this->new = FALSE;
                $this->modified = FALSE;
                return TRUE;
            } catch (Exception $e)
            {
                $this->db->rollback();
                throw new Exception("Impossible to securely save script " . $this->name);
            }
        } else
        {
            if ($this->modified)
            {

                if ($this->name == NULL or $this->cmd == NULL or $this->category == NULL)
                {
                    throw new Exception("Some fields are not set");
                }
                try
                {
                    $this->db->beginTransaction();
                    $upd = "UPDATE $this->sourceTab SET NAME=?, CMD=?, CATEGORY=? WHERE ID=?";
                    $sql = $this->db->prepare($upd);
                    $sql->execute(array($this->name, $this->cmd, $this->category, $this->id));
                    $this->db->commit();
                    $this->modified = FALSE;
                    return TRUE;
                } catch (Exception $e)
                {
                    $this->db->rollback();
                    throw new Exception("Impossible to securely save script " . $this->name);
                }
            }
        }
    }


} 