<?php

class myPDO
{
    private $connection = null;
    private static $instance = null;

    private function __construct()
    {
        $dns='mysql:host=localhost;dbname=420px_db';
        $user='420px_admin';
        $password='420px_admin';
        $option=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        $this->connection = new PDO($dns, $user, $password, $option);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new myPDO();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

