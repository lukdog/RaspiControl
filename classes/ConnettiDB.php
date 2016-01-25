<?php

/**
 * Created by PhpStorm.
 * User: Luca Doglione
 * Date: 26/08/14
 * Time: 16:14
 */



class ConnettiDB {

    private static $user = "";
    private static $pass = "";
    private static $nomeDB = "";
    private static $host = "";
    private static $connection = NULL;

    final private function __construct(){}

    final private function __clone(){}

    public static function getConnection(){
        if(self::$connection == NULL){

            try{
                self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$nomeDB,
                    self::$user, self::$pass);
            } catch (Exception $e) {
                echo $e->getMessage();
            }

        }
        return self::$connection;
    }

}
