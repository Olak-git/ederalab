<?php
namespace src\Repository;

use src\Entity\Dentiste;
use src\Vendor\MainRepository;

class CommandeRepository extends MainRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findCalendarCommandReceive(string $date)
    {
        $sql = 'SELECT * 
                FROM commande 
                WHERE date_envoie = :dat 
                AND archive = 0 
                AND valide != -1 
                AND livraison != 2';
        return $this->getData($sql, ['dat' => $date]);
    }

    public function findCommandeRecue()
    {
        $sql = 'SELECT * 
                FROM commande 
                WHERE archive = 0 
                AND valide = 0';
        return $this->getData($sql);
    }

    public function findCommandeLivree()
    {
        $sql = 'SELECT * 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 2';
        return $this->getData($sql);
    }

    public function findCommandeEnAttente()
    {
        $sql = 'SELECT * 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 0';
        return $this->getData($sql);
    }

    public function findCommandeAnnulee()
    {
        $sql = 'SELECT * 
                FROM commande 
                WHERE archive = 0 
                AND valide = -1';
        return $this->getData($sql);
    }

    public function totalCommandesRecues($year = null, $month = null)
    {
        $params = [];
        $sql = 'SELECT COUNT(id) n  
                FROM commande 
                WHERE archive = 0 
                AND valide = 0';
        if($year !== null) {
            $sql .= ' AND YEAR(date_envoie) = :year';
            $params['year'] = $year;
        }
        if($month !== null) {
            $sql .= ' AND MONTH(date_envoie) = :month';
            $params['month'] = $month;
        }
        $req = $this->execute($sql, $params);
        return $req->fetchColumn();
    }

    public function totalCommandesRecuesPlanification($year = null, $month = null)
    {
        $params = [];
        $sql = 'SELECT COUNT(id) n  
                FROM commande 
                WHERE archive = 0 
                AND valide != -1 
                AND livraison != 2';
        if($year !== null) {
            $sql .= ' AND YEAR(date_envoie) = :year';
            $params['year'] = $year;
        }
        if($month !== null) {
            $sql .= ' AND MONTH(date_envoie) = :month';
            $params['month'] = $month;
        }
        $req = $this->execute($sql, $params);
        return $req->fetchColumn();
    }

    public function totalCommandesLivrees($year = null, $month = null)
    {
        $params = [];
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 2';
        if($year !== null) {
            $sql .= ' AND YEAR(date_envoie) = :year';
            $params['year'] = $year;
        }
        if($month !== null) {
            $sql .= ' AND MONTH(date_envoie) = :month';
            $params['month'] = $month;
        }
        $req = $this->execute($sql, $params);
        return $req->fetchColumn();
    }

    public function totalCommandesEnattente()
    {
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = 1 
                AND livraison = 0';
        $req = $this->execute($sql);
        return $req->fetchColumn();
    }

    public function totalCommandesAnnulees()
    {
        $sql = 'SELECT COUNT(id) n 
                FROM commande 
                WHERE archive = 0 
                AND valide = -1';
        $req = $this->execute($sql);
        return $req->fetchColumn();
    }

    public function getOrdersCommandToday(Dentiste $o)
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE d.id = :did 
                AND c.date_envoie = :dat';

        return $this->getData($sql, ['did' => $o->getId(), 'dat' => date('Y-m-d')]);
    }

    public function getOrdersCommandLongTime(Dentiste $o)
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE d.id = :did 
                AND c.date_envoie < :dat';
        return $this->getData($sql, ['did' => $o->getId(), 'dat' => date('Y-m-d')]);
    }

    public function getOrdersArchivedToday()
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                WHERE c.archive = 1 
                AND c.date_archive = :dat';
        return $this->getData($sql, ['dat' => date('Y-m-d')]);
    }

    public function getOrdersArchivedYesterday()
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                WHERE c.archive = 1 
                AND c.date_archive = :dat';
        return $this->getData($sql, ['dat' => (new \DateTime('now'))->modify('-1days')->format('Y-m-d')]);
    }

    public function getOrdersArchivedLongTime()
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                WHERE c.archive = 1 
                AND c.date_archive < :dat';
        return $this->getData($sql, ['dat' => (new \DateTime('now'))->modify('-1days')->format('Y-m-d')]);
    }

    public function getCommandeByTransReceptionDate(string $date)
    {
        $sql = 'SELECT DISTINCT(c.id), c.* 
                FROM commande c 
                INNER JOIN choix_transporteur ct 
                    ON ct.commande = c.id 
                INNER JOIN transporteur t 
                    ON ct.transporteur = t.id 
                WHERE Date(ct.date_reception)=:dat';

        return $this->getData($sql, ['dat' => $date]);
    }

    public function totalCommandesLivreesForDentiste(int $dentiste_id)
    {
        $sql = 'SELECT COUNT(c.id) n 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE d.id = :did 
                AND livraison = 2';
        $req = $this->execute($sql, ['did' => $dentiste_id]);
        return $req->fetchColumn();
    }

    public function totalCommandesEncoursForDentiste(int $dentiste_id)
    {
        $sql = 'SELECT COUNT(c.id) n 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE d.id = :did 
                AND (livraison = 0 OR livraison = 1)';
        $req = $this->execute($sql, ['did' => $dentiste_id]);
        return $req->fetchColumn();
    }

    public function chartCommandeRecue(?int $dentiste_id = null)
    {
        $params = ['year' => date('Y')];
        $sql = 'SELECT COUNT(c.id) n, MONTH(`date_envoie`) m 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE YEAR(`date_envoie`) = :year ';
        if(null !== $dentiste_id) {
            $sql .= 'AND d.id = :did ';
            $params['did'] = $dentiste_id;
        }
        $sql .= 'GROUP BY MONTH(`date_envoie`) 
                ORDER BY m ASC';
        $req = $this->execute($sql, $params);
        return $req->fetchAll();
    }

    public function chartCommandeLivree(?int $dentiste_id = null)
    {
        $params = ['year' => date('Y')];
        $sql = 'SELECT COUNT(c.id) n, MONTH(`date_envoie`) m 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE c.valide = 1 
                AND c.livraison = 2 
                AND YEAR(`date_envoie`) = :year ';
        if(null !== $dentiste_id) {
            $sql .= 'AND d.id = :did ';
            $params['did'] = $dentiste_id;
        }
        $sql .= 'GROUP BY MONTH(`date_envoie`) 
                ORDER BY m ASC';
        $req = $this->execute($sql, $params);
        return $req->fetchAll();
    }

    public function chartCommandeEnAttente(?int $dentiste_id = null)
    {
        $params = ['year' => date('Y')];
        $sql = 'SELECT COUNT(c.id) n, MONTH(`date_envoie`) m 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE c.valide = 1 
                AND c.livraison = 0 
                AND YEAR(`date_envoie`) = :year ';
        if(null !== $dentiste_id) {
            $sql .= 'AND d.id = :did ';
            $params['did'] = $dentiste_id;
        }
        $sql .= 'GROUP BY MONTH(`date_envoie`) 
                ORDER BY m ASC';
        $req = $this->execute($sql, $params);
        return $req->fetchAll();
    }

    public function chartCommandeNonLivree(?int $dentiste_id = null)
    {
        $params = ['year' => date('Y')];
        $sql = 'SELECT COUNT(c.id) n, MONTH(`date_envoie`) m 
                FROM commande c 
                INNER JOIN dentiste d 
                    ON c.dentiste = d.id 
                WHERE c.valide = 1 
                AND c.livraison = -1 
                AND YEAR(`date_envoie`) = :year ';
        if(null !== $dentiste_id) {
            $sql .= 'AND d.id = :did ';
            $params['did'] = $dentiste_id;
        } 
        $sql .= 'GROUP BY MONTH(`date_envoie`) 
                ORDER BY m ASC';
        $req = $this->execute($sql, $params);
        return $req->fetchAll();
    }

    public function getCommandesForTransporter(int $id_transporter, string $date)
    {
        $sql = 'SELECT c.* 
                FROM commande c 
                INNER JOIN choix_transporteur ch 
                    ON ch.commande = c.id 
                INNER JOIN transporteur t 
                    ON ch.transporteur = t.id 
                WHERE t.id = :tid 
                AND DATE(date_reception) = :cdate';
        return $this->getData($sql, ['tid' => $id_transporter, 'cdate' => $date]);
    }
}