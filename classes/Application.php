<?php

/*
 * Application.php
 * Class that store information about the Running Instance of RaspiControl Application
 */

include_once dirname(__FILE__) . "/DBConnection.php";

error_reporting(E_ALL ^ E_DEPRECATED);

class Application
{

    //TODO Use appName and think about other attributes
    private static $app = NULL;
    private $db = NULL;
    private $config = "config.json";
    private $appName = "RaspiControl";
    private $configured = FALSE;
    private $userDB;
    private $hostDB;
    private $passDB;
    private $nameDB;
    private $outputDir;
    private $fsMonitor = NULL;

    final private function __construct()
    {
        $json = NULL;
        try
        {
            $text = file_get_contents($this->config);
            $json = json_decode($text, true);
        } catch (Exception $e)
        {
            throw new Exception("Config.ini presents some syntax errors");
        }

        if ($json == FALSE)
            throw new Exception("Impossible to Read config file");

        if (!isset($json['Database']['DB_Username']) || !isset($json['Database']['DB_Host']) || !isset($json['Database']['DB_Name']) || !isset($json['Database']['DB_Password']))
        {
            throw new Exception("You have to provide DB credential in config file");
        }

        $this->userDB = $json['Database']['DB_Username'];
        $this->hostDB = $json['Database']['DB_Host'];
        $this->nameDB = $json['Database']['DB_Name'];
        $this->passDB = $json['Database']['DB_Password'];

        if (!isset($json['runningScriptDir']))
            throw new Exception("You have to provide a directory for Running Script in config file");

        $this->outputDir = $json['runningScriptDir'];

        if (isset($json['App_Name']))
            $this->appName = $json['App_Name'];

        if (isset($json['FS_Monitor']))
            $this->fsMonitor = $json['FS_Monitor'];
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

    public function GetFS()
    {
        return $this->fsMonitor;
    }

    public function GetOutputDir()
    {
        return $this->outputDir;
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