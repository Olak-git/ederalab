<?php
namespace src\Vendor;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$dotenv->load();

class DB
{
    // Objet PDO d'accès à la BD
    protected $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO('mysql:host=' .$_ENV["DB_HOST"]. ';
                                    dbname=' .$_ENV["DB_NAME"]. ';
                                    charset=utf8', 
                                    $_ENV["DB_USER"], 
                                    $_ENV["DB_PASSWORD"], 
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