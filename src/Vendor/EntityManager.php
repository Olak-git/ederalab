<?php
namespace src\Vendor;

class EntityManager
{
    /**
     * @var \src\Vendor\DB
     */
    private $db;

    public function __construct()
    {
        $this->db = new DB;
    }

    /**
     * @param string $table
     * @param array $data
     */
    public function add($table, $data)
    {
        $sql = "INSERT INTO $table (";
        $index = "";
        $keys = array_keys($data);
        $L = count($keys);
        foreach($keys as $i => $key):
            $sql.=$key;
            $index.=":$key";
            if($i<$L-1) {
                $sql.=",";
                $index.=",";
            }
        endforeach;

        $sql.=") VALUES ($index)";

        $query = $this->db->query($sql, $data);
        // $query->closeCursor();
        return $this->db->getDb()->lastInsertId();
    }

    /**
     * @param string $table
     * @param array $data
     */
    public function addMultiple($table, $data)
    {
        $sql = "INSERT INTO $table (";
        $index = "";
        $keys = array_keys($data);
        $L = count($keys);
        foreach($keys as $i => $key):
            $sql.=$key;
            $index.=":$key";
            if($i<$L-1) {
                $sql.=",";
                $index.=",";
            }
        endforeach;

        $sql.=") VALUES ($index)";

        $this->db->query($sql, $data);
    }

    /**
     * @param string $table
     * @param array $data
     * @param mixed<array,int> $filter (format: [["id", "=", 2], ["age", ">=", 12], ...])
     */
    public function update($table, $data, $filter)
    {
        if(empty($data)) {
            return $this;
        }
        $sql = "UPDATE $table SET ";
        $keys = array_keys($data);
        $L = count($keys);
        foreach($keys as $i => $key):
            $sql.="{$key}=:{$key}";
            if($i<$L-1) {
                $sql.=",";
            }
        endforeach;

        $conditions = "";
        if(is_array($filter)) {
            $L = count($filter);
            foreach($filter as $i => $array):
                $key = $array[0];
                $k = $key;
                $operator = $array[1];
                $value = $array[2];
                if(array_key_exists($key, $data)) {
                    $k = "{$key}{$i}";
                }
                $conditions.="{$key} {$operator} :{$k}";
                if($i<$L-1) {
                    $conditions.=" AND ";
                }
                $data[$k] = $value;
            endforeach;
        } else {
            $conditions = "id=:idx";
            $data["idx"] = $filter;
        }

        if(!empty($conditions)) {
            $sql.=" WHERE $conditions";
        }

        $this->db->query($sql, $data);
    }

    /**
     * @param string $table
     * @param mixed<array,int> $filter (format: [["id", "=", 2], ["age", ">=", 12], ...])
     */
    public function delete($table, $filter)
    {
        $sql = "DELETE FROM $table";
        $conditions = "";
        $params = [];
        if(is_array($filter)) {
            $L = count($filter);
            foreach($filter as $i => $array):
                $key = $array[0];
                $operator = $array[1];
                $value = $array[2];

                $conditions.="{$key} {$operator} :{$key}";
                if($i<$L-1) {
                    $conditions.=" AND ";
                }
                $params[$key] = $value;
            endforeach;
        } else {
            $conditions = "id=:id";
            $params["id"] = $filter;
        }

        if(!empty($conditions)) {
            $sql.=" WHERE $conditions";
        }

        $this->db->query($sql, $params);
    }

    /**
     * @param string $table
     */
    public function count($table)
    {
        return $this->db->getDb()->query('SELECT id FROM ' . $table)->num_rows;
    }
}