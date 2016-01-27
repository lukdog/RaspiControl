<?php

/*
 * Creator: Luca Doglione
 *
 * DBConnection
 * class used to instantiate a connection to a database
 * it respect singleton pattern, and avoid multiple connection to db,
 * in this way there is only one connection to used DB
 */

//TODO: possibility to use sqlite and not mysql
class DBConnection
{

    private static $user = "";
    private static $pass = "";
    private static $DBname = "";
    private static $host = "";
    private static $connection = NULL;

    final private function __construct()
    {

        try
        {
            self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$DBname,
                self::$user, self::$pass);
        } catch (PDOException $e)
        {
            echo $e->getMessage();
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
