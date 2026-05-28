<?php
namespace src\Controller;

use src\Vendor\DB;
use src\Vendor\Security;
use src\Vendor\EntityManager;

class AdminController extends Security
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    public function isBlank($form, $key, $index)
    {
        if(empty($form[$key])) {
            $this->addError($index, 'Est requis');
            return true;
        } else {
            if(trim($form[$key]) == '') {
                $this->addError($index, 'Ne peut pas être vide');
                return true;
            }
        }
        return false;
    }

    public function adminUpdateProfil($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('admin-update-id', $post['csrf'])) {
                $form = $post['admin_update_id'];
                $data = [];

                if(!empty($form['identifiant'])) {
                    if(preg_match('#[ ]+#i', $form['identifiant'])) {
                        $this->addError('identifiant', 'L\'identifiant ne doit contenir aucun espace.');
                    } else {
                        $data['identifiant'] = password_hash($form['identifiant'], 1);
                    }
                }

                if(!$this->isBlank($form, 'email', 'email')) {
                    if(!preg_match('/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $form['email'])) {
                        $this->addError('email', 'Adresse email non valide.');
                    } else {
                        $data['email'] = $form['email'];
                    }
                }

                if(!$this->hasError()) {
                    $admin = array_merge($admin, $data);
                    (new EntityManager)->update("admin", $data, $admin["id"]);

                    $_SESSION['admin'] = serialize($admin);

                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Profil modifié avec succès.');
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            } 
        }
    }
}
