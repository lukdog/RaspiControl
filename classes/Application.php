<?php

/*
 * Application.php
 * Class that store information about the Running Instance of RaspiControl application
 */

include_once dirname(__FILE__) . "/DBConnection.php";

error_reporting(E_ALL ^ E_DEPRECATED);

class Application
{

    //TODO Use appName and think about other attributes
    private static $app = NULL;
    private $db = NULL;
    private $config = "config.ini";
    private $appName = "RaspiControl";
    private $configured = FALSE;
    private $userDB;
    private $hostDB;
    private $passDB;
    private $nameDB;

    final private function __construct()
    {
        $ini = NULL;
        try
        {
            $ini = parse_ini_file($this->config);
        } catch (Exception $e)
        {
            throw new Exception("Config.ini presents some syntax errors");
        }

        if ($ini == FALSE)
            throw new Exception("Impossible to Read config file");

        if (!isset($ini['DB_Username']) || !isset($ini['DB_Host']) || !isset($ini['DB_Password']) || !isset($ini['DB_Name']))
        {
            throw new Exception("You have to provide DB credential in config.ini file");
        }

        $this->userDB = $ini['DB_Username'];
        $this->hostDB = $ini['DB_Host'];
        $this->nameDB = $ini['DB_Name'];
        $this->passDB = $ini['DB_Password'];

        if (isset($ini['App_Name']))
            $this->appName = $ini['App_Name'];
    }

    public static function getAppInfo()
    {
        if (self::$app == NULL)
            self::$app = new Application();

        return self::$app;
    }

    public function GetDBHost()
    {
        return $this->hostDB;
    }

    public function GetDBName()
    {
        return $this->nameDB;
    }

    public function GetDBUser()
    {
        return $this->userDB;
    }

    public function GetDBPass()
    {
        return $this->passDB;
    }

    public function IsConfigured()
    {
        if ($this->configured) return TRUE;

        if ($this->db == NULL) $this->db = DBConnection::getConnection();

        $query = "SELECT COUNT(*) AS C FROM USERS";
        try
        {
            $res = $this->db->query($query);
            if ($res->rowCount() != 1)
                throw new Exception();
            else
            {
                $info = $res->fetch(PDO::FETCH_ASSOC);
                $this->configured = ($info['C'] > 0);
            }
        } catch (Exception $e)
        {
            throw new Exception("Impossible to Read Application Status");
        }

        return $this->configured;

    }

    final private function __clone()
    {
    }


}