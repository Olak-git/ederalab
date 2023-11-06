<?php
namespace src\Repository;

use src\Vendor\MainRepository;

class UserRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function totalUsers()
    {
        $sql = 'SELECT COUNT(id) n 
                FROM user';
        $req = $this->execute($sql);
        return $req->fetchColumn();
    }

    public function totalPrestataires()
    {
        $sql = 'SELECT COUNT(id) n 
                FROM user 
                WHERE user.prestataire = 1';
        $req = $this->execute($sql);
        return $req->fetchColumn();
    }

    public function totalClients()
    {
        $sql = 'SELECT COUNT(id) n 
                FROM user  
                WHERE user.prestataire = 0';
        $req = $this->execute($sql);
        return $req->fetchColumn();
    }
}