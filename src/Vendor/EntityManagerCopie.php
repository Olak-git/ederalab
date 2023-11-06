<?php
namespace src\Vendor;

use Exception;
use ReflectionClass;
use src\traits\Meithods;

use function src\Controller\dd;

class EntityManagerCopie
{
    use Meithods;

    private $db;

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

    public function add(EntityInterface $o)
    {
        $fr = new \ReflectionClass($o);
        $table = $this->formatage(str_replace('src/Entity/', '', str_replace('\\', '/', $fr->getName())));
        $properties = $fr->getProperties();
        $length = count($properties);
        $params = [];
        $sql = 'INSERT INTO ' . $table . '(';
        $values = '';
        foreach($properties as $i => $property):
            if(strtolower($property->getName()) !== 'id'):
                $sql .= $property->getName() . '';
                $values .= ':' . $property->getName();
                if($i < $length - 1) {
                    $sql .= ', ';
                    $values .= ', ';
                }
                $method = 'get' . ucfirst(str_replace('_', '', $property->getName()));
                if(method_exists($o, $method)) {
                    if(strtolower($property->getName()) === 'dat' && ($o->getDat() === '' || is_null($o->getDat()))) {
                        $o->setDat(date('Y-m-d H:i:s'));
                    }
                    $params[$property->getName()] = strtolower($property->getName()) === 'password' ? password_hash($o->$method(), 1) : (is_object($o->$method()) ? $o->$method()->getId() : $o->$method());
                }
            endif;
        endforeach;

        $sql .= ') VALUES (' . $values . ')';
        $req = $this->db->getDb()->prepare($sql);
        
        try{
            $req->execute($params);
            $o->setId($this->db->getDb()->lastInsertId());
        } catch(Exception $e) {
            die($e->getMessage());
        }
        $req->closeCursor();
    }

    public function update(EntityInterface $o)
    {
        $fr = new \ReflectionClass($o);
        $table = $this->formatage(str_replace('src/Entity/', '', str_replace('\\', '/', $fr->getName())));
        // if(strtolower($table) === 'genrezik') {
        //     $table = 'genre_zik';
        // }
        // if(strtolower($table) === 'preferenceartiste') {
        //     $table = 'preference_artiste';
        // }
        $properties = $fr->getProperties();
        $length = count($properties) - 1;
        $params = [];
        $sql = 'UPDATE ' . $table . ' SET ';
        foreach($properties as $i => $property):
            if(strtolower($property->getName()) !== 'password'):
                $sql .= $property->getName() . '=:' . $property->getName();
                if($i < $length) {
                    $sql .= ', ';
                }
                $method = 'get' . ucfirst(str_replace('_', '', $property->getName()));
                if(method_exists($o, $method) && 'password' !== strtolower($property->getName())) {
                    // if(strtolower($property->getName()) === 'dat') {
                    //     $o->setDat(date('Y-m-d H:i:s'));
                    // }
                    $params[$property->getName()] = strtolower($property->getName()) === 'password' ? password_hash($o->$method(), 1) : (is_object($o->$method()) ? $o->$method()->getId() : $o->$method());
                }
            endif;
        endforeach;

        $sql .= ' WHERE id = :id';

        $req = $this->db->getDb()->prepare($sql);
        
        try{
            $req->execute($params);
        } catch(Exception $e) {
            die($e->getMessage());
        }
        $req->closeCursor();
    }

    public function updatePassword(EntityInterface $o)
    {
        $fr = new \ReflectionClass($o);
        $table = $this->formatage(str_replace('src/Entity/', '', str_replace('\\', '/', $fr->getName())));
        // if(strtolower($table) === 'genrezik') {
        //     $table = 'genre_zik';
        // }
        // if(strtolower($table) === 'preferenceartiste') {
        //     $table = 'preference_artiste';
        // }
        $sql = 'UPDATE ' . $table . ' SET password=:password WHERE id=:id';
        $params = ['password' => password_hash($o->getPassword(), 1), 'id' => $o->getId()];
        $req = $this->db->getDb()->prepare($sql);
        
        try{
            $req->execute($params);
        } catch(Exception $e) {
            die($e->getMessage());
        }
        $req->closeCursor();
    }

    public function remove(EntityInterface $o)
    {
        $fr = new \ReflectionClass($o);
        $table = $this->formatage(str_replace('src/Entity/', '', str_replace('\\', '/', $fr->getName())));
        // if(strtolower($table) === 'genrezik') {
        //     $table = 'genre_zik';
        // }
        // if(strtolower($table) === 'preferenceartiste') {
        //     $table = 'preference_artiste';
        // }
        $sql = 'DELETE FROM ' . $table . ' WHERE id = :id';
        $req = $this->db->getDb()->prepare($sql);
        $method = 'getId';
        if(method_exists($o, $method)) {
            $req->execute(['id' => $o->$method()]);
        }
        $req->closeCursor();
    }

    public function removeAll(array $s)
    {
        foreach ($s as $key => $o) {
            $this->remove($o);
        }
    }

    public function count($table)
    {
        return $this->db->getDb()->query('SELECT id FROM ' . $table)->num_rows;
    }
}