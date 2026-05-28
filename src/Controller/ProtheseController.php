<?php
namespace src\Controller;

use src\Vendor\DB;
use src\traits\Properties;
use src\Vendor\Security;
use src\Vendor\EntityManager;

class ProtheseController extends Security
{
    use Properties;

    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new DB;
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

    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    public function addProthese($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('admin-add-prothese', $post['csrf'])) {
                $form = $post['cas_prothese'];
                $data = [];
    
                if(!$this->isBlank($form, 'nom', 'nom')) {
                    $data['nom'] = trim($form['nom']);
                }
                if(!$this->isBlank($form, 'numero', 'numero')) {
                    $data['numero'] = trim($form['numero']);
                }
                if(!$this->isBlank($form, 'detail', 'detail')) {
                    $data['detail'] = trim($form['detail']);
                }
                if(!$this->hasError()) {
                    $data['slug'] = $this->createSlug();
                    (new EntityManager)->add("prothese", $data);
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Un nouveau cas de prothese a été ajouté avec succès.');
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }
}