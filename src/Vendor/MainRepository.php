<?php
namespace src\Vendor;

use src\Vendor\DB;

use src\Entity\Admin;
use src\Entity\Facture;
use src\Entity\Message;
use src\Entity\Commande;
use src\Entity\Dentiste;
use src\Entity\Prothese;
use src\traits\Meithods;
use src\Entity\Discussion;
use function src\Router\dd;
use src\Entity\Identifiant;
use src\Entity\LastMessage;
use src\Entity\Transporteur;
use src\Entity\ChoixProthese;
use src\Entity\ChoixTransporteur;

abstract class MainRepository
{
    use Meithods;

    // Objet PDO d'accès à la BD
    protected $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    public function getTable()
    {
        $fr = new \ReflectionClass($this);
        $table = str_replace(['src/Entity/', 'src/Repository/', 'Repository'], '', str_replace('\\', '/', $fr->getName()));
        return $table;
    }

    // Exécute une requête SQL éventuellement paramétrée
    protected function execute($sql, $params = null) 
    {
        if ($params == null) {
            $resultat = $this->db->getDb()->query($sql); // exécution directe
        }
        else {
            $resultat = $this->db->getDb()->prepare($sql); // requête préparée
            $resultat->execute($params);
        }

        return $resultat;
    }

    public function generateObject($data)
    {
        $object = null;
        switch (strtolower($this->formatage($this->getTable()))) {
            
            case 'admin':
                $object = new Admin($data);
                break;

            case 'choix_prothese':
                $object = new ChoixProthese($data);
                break;

            case 'choix_transporteur':
                $object = new ChoixTransporteur($data);
                break;

            case 'commande':
                $object = new Commande($data);
                break;

            case 'dentiste':
                $object = new Dentiste($data);
                break;

            case 'discussion':
                $object = new Discussion($data);
                break;

            case 'facture':
                $object = new Facture($data);
                break;

            case 'identifiant':
                $object = new Identifiant($data);
                break;

            case 'last_message':
                $object = new LastMessage($data);
                break;

            case 'message':
                $object = new Message($data);
                break;

            case 'prothese':
                $object = new Prothese($data);
                break;

            case 'transporteur':
                $object = new Transporteur($data);
                break;

            default:
                # code...
                break;
        }
        return $object;
    }

    public function getData($sql, array $params = [])
    {
        $data = $this->execute($sql, $params)->fetchAll();
        foreach($data as $i => $v):
            $data[$i] = $this->generateObject($v);
        endforeach;
        return $data;
    }

    public function findAll(array $orderBy = [])
    {
        $sql = 'SELECT * FROM ' . $this->formatage($this->getTable());

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

        $data = $this->execute($sql)->fetchAll();
        foreach($data as $i => $v):
            $data[$i] = $this->generateObject($v);
        endforeach;
        return $data;
    }

    public function findOneBy(array $filter, array $orderBy = [], int $limit = -1, int $offs = 0)
    {
        $result = $this->find($filter);
        if ($result->rowCount() >= 1) {
            return $this->generateObject(
                $result->fetch()
            ); // Accès à la première ligne de résultat
        }
        else return null;
            // throw new \Exception('Aucune résultat ne correspond à ce que vous rechercher');
    }

    public function findBy(array $filter, array $orderBy = [], int $limit = -1, int $offs = 0)
    {
        $data = $this->find($filter, $orderBy, $limit, $offs)->fetchAll();
        foreach($data as $i => $v):
            $data[$i] = $this->generateObject($v);
        endforeach;
        return $data;
    }

    protected function find(array $filter, array $orderBy = [], int $limit = -1, int $offs = 0)
    {
        $keys = array_keys($filter);
        $count = count($keys);
        $sql = 'SELECT * FROM ' . $this->formatage($this->getTable());
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
            $sql .= ' LIMIT ' . $offs . ', ' . $limit;
        }

        return $this->execute($sql, $filter);
    }
}