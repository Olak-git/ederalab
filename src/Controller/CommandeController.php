<?php
namespace src\Controller;

use src\Vendor\DB;
use src\Vendor\Security;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\traits\Properties;

class CommandeController extends Security
{
    use Properties;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    public function valideDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    public function attributedTransporteur($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('choice-transporter', $post['csrf']))
            {
                $form = $post['ch_transp'];
                if(empty($form['commande'])) {
                    $this->addError('commande', 'Une erreur non identifiée a été repérée.');
                } else {
                    $commande = $this->db->findOneBy("commande", ['id' => $form['commande']]);
                    if(null == $commande) {
                        $this->addError('commande', 'Commande non identifiée.');
                    }
                }

                if(empty($form['transporteur'])) {
                    $this->addError('transporteur', 'Une erreur non identifiée a été repérée.');
                } else {
                    $transporteur = $this->db->findOneBy("transporteur", ['id' => $form['transporteur']]);
                    if(null == $transporteur) {
                        $this->addError('transporteur', 'Transporteur non identifiée.');
                    }
                }

                if(!$this->hasError()) {
                    $choixTransporter = $this->db->findOneBy("choix_transporteur", ['commande' => $commande["id"], 'transporteur' => $transporteur["id"]]);
                    if(!$choixTransporter) {
                        (new EntityManager)->add("choix_transporteur", [
                            "slug" => $this->createSlug(),
                            'commande' => $commande["id"],
                            'transporteur' => $transporteur["id"],
                            'date_reception' => date('Y-m-d H:i:s')
                        ]);
                    }
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Votre transporteur est prêt à réceptionner votre commande.');
                }
            }
        }
    }

    public function acceptCommand($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('accept-command', $post['csrf']))
            {
                if(empty($post['accept_cmd']['commande'])) {
                    $this->addError('error', 'Erreur non identifiée survenue.');
                } else {
                    $commande = $this->db->findOneBy("commande", ['id' => $post['accept_cmd']['commande']]);
                    if($commande) {
                        (new EntityManager)->update("commande", ["valide" => 1], $commande["id"]);

                        $this->setShowNotification(true);
                        $this->setNotificationColor('bg-success');
                        $this->addNotification('Commande acceptée.');
                    } else {
                        $this->addError('error', 'Erreur non identifiée survenue.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
        }
    }

    public function cancelCommand($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('cancel-command', $post['csrf']))
            {
                if(empty($post['cancel_cmd']['commande'])) {
                    $this->addError('error', 'Erreur non identifiée survenue.');
                } else {
                    $commande = $this->db->findOneBy("commande", ['id' => $post['cancel_cmd']['commande']]);
                    if($commande) {
                        (new EntityManager)->update("commande", ["valide" => -1], $commande["id"]);

                        $this->setShowNotification(true);
                        $this->setNotificationColor('bg-success');
                        $this->addNotification('Commande refusée.');
                    } else {
                        $this->addError('error', 'Erreur non identifiée survenue.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
        }
    }

    public function addToArchive($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('add-archive', $post['csrf']))
            {
                $form = $post['archive'];
                if(empty($form['commande'])) {
                    $this->addError('commande', 'Une erreur non identifiée a été repérée.');
                } else {
                    $commande = $this->db->findOneBy("commande", ['id' => $form['commande']]);
                    if(null == $commande) {
                        $this->addError('commande', 'Commande non identifiée.');
                    } else {
                        (new EntityManager)->update("commande", ["archive" => 1, "date_archive" => date('Y-m-d')], $commande["id"]);

                        $this->setShowNotification(true);
                        $this->setNotificationColor('bg-success');
                        $this->addNotification('Commande archivée avec succès.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
        }
    }

    private function isNotBlank(array $array, string $index, string $key)
    {
        if(empty($array[$index])) {
            $this->addError($key, 'Est requis.');
            return false;
        } else {
            if(trim($array['nom_dentiste']) == '') {
                $this->addError($key, 'Ne peut pas être vide.');
                return false;
            }
        }
        return true;
    }

    public function createCommand($post, $files)
    {
        if($dentiste = $this->getDentiste()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('add-command', $post['csrf']))
            {
                $protheses = [];
                $data = [];
                $form = $post['add_commande'];
                if($this->isNotBlank($form, 'nom_dentiste', 'nom_dentiste')) {
                    $data['nom_dentiste'] = $form['nom_dentiste'];
                }

                if($this->isNotBlank($form, 'cabinet', 'cabinet')) {
                    $data['cabinet'] = $form['cabinet'];
                }

                if($this->isNotBlank($form, 'nom_patient', 'nom_patient')) {
                    $data['nom_patient'] = $form['nom_patient'];
                }

                if($this->isNotBlank($form, 'prenom_patient', 'prenom_patient')) {
                    $data['prenom_patient'] = $form['prenom_patient'];
                }

                // if($this->isNotBlank($form, 'nom_patient', 'nom_patient')) {

                // }

                if($this->isNotBlank($form, 'description_specifiq', 'description_specifiq')) {
                    $data['description_specifiq'] = $form['description_specifiq'];
                }

                if(!empty($form['description_libre'])) {
                    $data['description_libre'] = $form['description_libre'];
                }

                if(empty($form['cas_prothese'])) {
                    $this->addError('prothese', 'Veuillez indiquer le cas de prothèse que vous voulez.');
                } else {
                    foreach($form['cas_prothese'] as $ky => $pro) {
                        $prothese = $this->db->findOneBy("prothese", ['id' => $pro]);
                        if(!$prothese) {
                            $this->addError('prothese', 'Prothèse non identifiée.');
                        } else {
                            $protheses[] = [
                                'prothese' => $prothese["id"],
                                'cas_num' => $prothese["numero"],
                                'modif_demand' => $form['modification_dmd'][$ky]
                            ];
                        }
                    }
                }

                if($this->isNotBlank($form, 'date_reception', 'date_reception')) {
                    $data['date_envoie'] = date('Y-m-d');
                    if(preg_match('#[0-9]{2}/[0-9]{2}/[0-9]{4}#', $form['date_reception'])) {
                        $explode = explode('/', $form['date_reception']);
                        $d = $explode[0];
                        $m = $explode[1];
                        $y = $explode[2];
                        if(checkdate($m, $d, $y)) {
                            if($this->valideDate($y . '-' . $m . '-' . $d)) {
                                $data['date_reception_prevue'] = $y . '-' . $m . '-' . $d;
                            } else {
                                $this->addError('date_reception', 'Format non valide: JJ/MM/AAAA');
                            }
                        } else {
                            $this->addError('date_reception', 'Format non valide: JJ/MM/AAAA');
                        }
                    } else {
                        $this->addError('date_reception', 'Format non valide: JJ/MM/AAAA');
                    }
                }

                if(isset($files['piece_jointe']) && $files['piece_jointe']['name'] !== '') {
                    $fileService = new FileService($files['piece_jointe'], 'doc', 'images/files', !$this->hasError(), 5, 'M', false);
                    if($fileService->saveFile()) {
                        $data['piece_jointe'] = $fileService->getName();
                    } elseif($fileService->getError()) {
                        $this->addError('piece_jointe', $fileService->getError());
                    }
                }

                if(!$this->hasError()) {
                    $em = new EntityManager;

                    $data['dentiste'] = $dentiste["id"];
                    $id = $em->add("commande", array_merge($data, [
                        "archive" => 0,
                        "valide" => 0,
                        "livraison" => 0,
                        "slug" => $this->createSlug()
                    ]));

                    foreach($protheses as $pro) {
                        $pro['commande'] = $id;
                        $pro['nom_patient'] = ucwords($data['nom_patient'] . ' ' . $data['prenom_patient']);
                        $pro["slug"] = $this->createSlug();
                        $em->add("choix_prothese", $pro);
                    }

                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Commande envoyée avec succès.');
                }
            }
        }
    }

    public function setStateDelivery($post)
    {
        if($dentiste = $this->getDentiste()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('delivery-command', $post['csrf']))
            {
                $form = $post['delivery'];
                if(!empty($form['command'])) {
                    $commande = $this->db->findOneBy("commande", ['dentiste' => $dentiste["id"], 'valide' => 1, 'slug' => $form['command']]);
                    if($commande) {
                        $data = [];
                        if($form['v'] == 2) {
                            $data["livraison"] = 2;
                            $data["date_livraison"] = date('Y-m-d');
                        } elseif($form['v'] == -1) {
                            $data["livraison"] = -1;
                        }
                        (new EntityManager)->update("commande", $data, $commande["id"]);
                    } else {
                        $this->addError('command', '');
                    }
                } else {
                    $this->addError('command', '');
                }
            }
        }
    }
}
