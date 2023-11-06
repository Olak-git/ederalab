<?php
namespace src\Vendor;

use PDO;

class DB
{
    protected const SERVERNAME = 'localhost';
    protected const DBNAME = 'ederalab';
    protected const USER = 'project_root';
    protected const PASSWORD = 'default';

    // Objet PDO d'accès à la BD
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO('mysql:host=' .self::SERVERNAME. ';
                                    dbname=' .self::DBNAME. ';
                                    charset=utf8', 
                                    self::USER, 
                                    self::PASSWORD, 
                                    array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
                            );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getDb()
    {
        return $this->db;
    }
}