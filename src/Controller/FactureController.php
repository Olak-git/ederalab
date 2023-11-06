<?php
namespace src\Controller;

use src\Entity\Facture;
use src\Vendor\Security;
use src\Vendor\EntityManager;
use src\Repository\CommandeRepository;

class FactureController extends Security
{
    public function __construct()
    {
        parent::__construct();
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

    /**
     * @param string $value: la valeur dont on veut vérifier l'exactitude
     * @param string $csrf: le csrf envoyé par le formulaire 
     */
    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    public function createFacture($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('admin-create-facture', $post['csrf']))
            {
                $form = $post['new_facture'];
                $data = [];

                if(!empty($form['cmd'])) {
                    $cmd = (new CommandeRepository)->findOneBy(['valide' => 1, 'livraison' => 2, 'slug' => $form['cmd']]);
                    if($cmd) {
                        $data['commande'] = $cmd->getId();
                        
                        if(!$this->isBlank($form, 'total_ht', 'total_ht')) {
                            if(preg_match('#^[0-9]+[.]?[0-9]*#', $form['total_ht'])) {
                                $data['total_ht'] = $form['total_ht'];
                            } else {
                                $this->addError('total_ht', 'Doit être un nombre.');
                            }
                        }
        
                        if(!$this->isBlank($form, 'tva', 'tva')) {
                            if(preg_match('#^[0-9]+[.]?[0-9]*#', $form['tva'])) {
                                $data['tva'] = $form['tva'];
                            } else {
                                $this->addError('tva', 'Doit être un nombre.');
                            }
                        }
        
                        if(!$this->isBlank($form, 'devis', 'devis')) {
                            $data['devis'] = $form['devis'];
                        }
        
                        if(!$this->hasError()) {
                            $facture = new Facture($data);
                            $em = new EntityManager;
                            $em->add($facture);

                            $this->setShowNotification(true);
                            $this->setNotificationColor('bg-success');
                            $this->addNotification('Facture envoyée avec succès.');
                        }
                    } else {
                        $this->addError('cmd', 'Une erreur non identifiée survenue.');
                    }
                } else {
                    $this->addError('cmd', 'Une erreur non identifiée survenue.');
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
        }
    }
}