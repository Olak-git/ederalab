<?php
namespace src\Vendor;

use src\Vendor\Outil;

if( ! session_id() ){session_start();}

require '../vendor/autoload.php';

class Security extends Outil
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAdmin()
    {
        if(isset($_SESSION['admin'])) {
            return unserialize($_SESSION['admin']);
        }
        return null;
    }

    public function getDentiste()
    {
        if(isset($_SESSION['dentiste'])) {
            return unserialize($_SESSION['dentiste']);
        }
        return null;
    }

    public function getTransporteur()
    {
        if(isset($_SESSION['transporteur'])) {
            return unserialize($_SESSION['transporteur']);
        }
        return null;
    }


}