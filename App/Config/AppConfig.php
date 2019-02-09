<?php
/**
 * Created by PhpStorm.
 * User: Patrik
 * Date: 09/02/2019
 * Time: 18:22
 */

namespace App\Config;

class AppConfig
{

    private $config;

    public function __construct()
    {
        $this->config = array(
            'host' => 'localhost',
            'db' => 'uhp',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        );
    }

    public function getConfig() {
        return $this->config;
    }

}