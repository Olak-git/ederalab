<?php
namespace src\Vendor;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../..");
$dotenv->load();

class DB
{
    /**
     * @var \PDO
     */
    protected $db;

    public function __construct()
    {
        $currentDir = __DIR__;
        // Construire le chemin absolu vers ca.pem
        $caCertPath = $currentDir . DIRECTORY_SEPARATOR . 'ca.pem'; // '/etc/ssl/cert.pem';

        $conn = "pgsql:"; // pgsql
        $conn .= "host={$_ENV['DB_HOST']};";
        $conn .= "port={$_ENV['DB_PORT']};";
        $conn .= "dbname={$_ENV['DB_NAME']};";

        if(preg_match("/^(pgsql)/",$conn)) {
            // Paramètres SSL
            // $conn .= "sslmode=require;";
            $conn .= "sslmode=verify-ca;sslrootcert={$caCertPath};";

            $conn .= "options='--client_encoding=UTF8'";
        } else {
            // Paramètres SSL
            $conn .= "sslmode=require;";
            // $conn .= "sslmode=verify-ca;sslrootcert={$caCertPath};";

            $conn .= "charset=utf8";
        }
        
        try {
            $this->db = new \PDO(
                $conn, 
                $_ENV["DB_USER"], 
                $_ENV["DB_PASSWORD"], 
                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                )
            );
        } catch(\PDOException $e) {
            echo "Erreur complète : " . $e->getMessage() . "<br>";
            echo "Code d'erreur : " . $e->getCode() . "<br>";
            die("Trace : " . $e->getTraceAsString());
        }
    }

    public function getDb()
    {
        return $this->db;
    }

    // Exécute une requête SQL éventuellement paramétrée
    protected function execute(string $sql, $params = null) 
    {
        $resultat = $this->db->prepare($sql);
        if ($params == null) {
            $resultat->execute([]);
        }
        else {
            $resultat->execute($params);
        }

        return $resultat;
    }

    public function getData(string $sql, array $params = [])
    {
        $request = $this->execute($sql, $params);
        $data = $request->fetchAll();
        $request->closeCursor();
        return $data;
    }

    public function findAll(string $table, array $orderBy = [])
    {
        $sql = "SELECT * FROM $table";

        $count = count($orderBy);
        $i = 0;
        foreach($orderBy as $k => $o_b):
            if($i === 0) {
                $sql .= ' ORDER BY ';
            }
            $sql .= "$k $o_b";
            if($i < $count - 1) {
                $sql .= ', ';
            }
            $i++;
        endforeach;

        $request = $this->execute($sql);
        $data = $request->fetchAll();
        $request->closeCursor();
        return $data;
    }

    public function findOneBy(string $table, array $filter)
    {
        $request = $this->find($table, $filter, ['id' => 'DESC'], 1, 0);
        $data = null;
        if ($request->rowCount() >= 1) {
            $data = $request->fetch();
        }
        $request->closeCursor();
        return $data;
    }

    public function findBy(string $table, array $filter, array $orderBy = [], int $limit = -1, int $offs = 0)
    {
        $request = $this->find($table, $filter, $orderBy, $limit, $offs);
        $data = $request->fetchAll();
        $request->closeCursor();
        return $data;
    }

    public function query(string $sql, $params = null)
    {
        return $this->execute($sql, $params);
    }

    protected function find(string $table, array $filter, array $orderBy = [], int $limit = -1, int $offs = 0)
    {
        $keys = array_keys($filter);
        $count = count($keys);
        $sql = "SELECT * FROM $table";
        if(!empty($filter)) {
            $sql .= ' WHERE ';
        }
        foreach($keys as $ind => $key):
            $sql .= $key . '=:' . $key;
            if($ind < $count - 1) {
                $sql .= ' AND ';
            }
        endforeach;

        $count = count($orderBy);
        $i = 0;
        foreach($orderBy as $k => $o_b):
            if($i === 0) {
                $sql .= ' ORDER BY ';
            }
            $sql .= $k . ' ' . $o_b;
            if($i < $count - 1) {
                $sql .= ', ';
            }
            $i++;
        endforeach;

        if($limit > 0) {
            $sql .= " LIMIT $limit OFFSET $offs";
        }

        return $this->execute($sql, $filter);
    }
}