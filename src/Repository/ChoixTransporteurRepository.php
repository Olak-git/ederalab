<?php
namespace src\Repository;

use src\Vendor\MainRepository;

class ChoixTransporteurRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getChoixForCommande(int $id, string $date)
    {
        $sql = 'SELECT c.* 
                FROM choix_transporteur c 
                INNER JOIN transporteur t 
                    ON c.transporteur = t.id 
                WHERE t.id = :tid 
                AND DATE(date_reception) = :cdate';
        return $this->getData($sql, ['tid' => $id, 'cdate' => $date]);
    }
}