<?php
namespace src\Repository;

use src\Vendor\MainRepository;

class FactureRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getFactureForDentiste(int $dentiste_id)
    {
        $sql = 'SELECT * 
                FROM facture f 
                INNER JOIN commande c 
                    ON f.commande = c.id 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE d.id = :did';
        return $this->getData($sql, ['did' => $dentiste_id]);
    }
}