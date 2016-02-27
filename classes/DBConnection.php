<?php

/*
 * Creator: Luca Doglione
 *
 * DBConnection
 * class used to instantiate a connection to a database
 * it respect singleton pattern, and avoid multiple connection to db,
 * in this way there is only one connection to used DB
 */

include_once dirname(__FILE__) . "/Application.php";

//TODO: possibility to use sqlite and not mysql
class DBConnection
{

    private static $connection = NULL;

    final private function __construct()
    {

        try
        {
            $a = Application::getAppInfo();

            self::$connection = new PDO("mysql:host=" . $a->GetDBHost() . ";dbname=" . $a->GetDBName(),
                $a->GetDBUser(), $a->GetDBPass());
        } catch (PDOException $e)
        {
            throw new Exception("Impossible to establish connection to DB");
        }

    }

    final private function __clone()
    {
    }

    public static function getConnection()
    {
        if (!self::$connection)
            new DBConnection();

        return self::$connection;
    }

}
