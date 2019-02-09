<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 18:11
 */

namespace App;

use App\Config\AppConfig;


/*
 * Class to connect to the database.
 */
class DB
{
    static private $PDOInstance;

    public function __construct()
    {
        if(!self::$PDOInstance) {

            try {

                $appConfig = new AppConfig();
                $config = $appConfig->getConfig();

                $host = $config['host'];
                $db   = $config['db'];
                $username = $config['username'];
                $password = $config['password'];
                $charset = $config['charset'];
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";



                $options = [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                self::$PDOInstance = new \PDO($dsn, $username, $password, $options);
            } catch (\PDOException $e) {
                die("Database connection error: " . $e->getMessage() . "<br/>");
            }
        }

        return self::$PDOInstance;
    }


    public function getDB() {
        return self::$PDOInstance;
    }
}