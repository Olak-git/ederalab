<?php
namespace src\Vendor;

use src\Entity\Admin;
use src\Entity\Dentiste;
use src\Entity\Transporteur;
use src\Vendor\Outil;

if( ! session_id() ){session_start();}

require '../vendor/autoload.php';

class Security extends Outil
{
    public function __construct()
    {
        parent::__construct();
    }

    // public function getRoutes(): Routes
    // {
    //     return $this->routes;
    // }

    public function getAdmin(): ?Admin
    {
        if(isset($_SESSION['admin'])) {
            return unserialize($_SESSION['admin']);
        }
        return null;
    }

    public function getDentiste(): ?Dentiste
    {
        if(isset($_SESSION['dentiste'])) {
            return unserialize($_SESSION['dentiste']);
        }
        return null;
    }

    public function getTransporteur(): ?Transporteur
    {
        if(isset($_SESSION['transporteur'])) {
            return unserialize($_SESSION['transporteur']);
        }
        return null;
    }


}