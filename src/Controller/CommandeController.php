<?php
namespace src\Controller;

use src\Entity\Commande;
use src\Vendor\Security;
use src\Entity\ChoixProthese;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\Entity\ChoixTransporteur;
use src\Repository\CommandeRepository;
use src\Repository\ProtheseRepository;
use src\Repository\TransporteurRepository;
use src\Repository\ChoixTransporteurRepository;

class CommandeController extends Security
{
    public function __construct()
    {
        parent::__construct();
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
                    $commande = (new CommandeRepository)->findOneBy(['id' => $form['commande']]);
                    if(null == $commande) {
                        $this->addError('commande', 'Commande non identifiée.');
                    }
                }

                if(empty($form['transporteur'])) {
                    $this->addError('transporteur', 'Une erreur non identifiée a été repérée.');
                } else {
                    $transporteur = (new TransporteurRepository)->findOneBy(['id' => $form['transporteur']]);
                    if(null == $transporteur) {
                        $this->addError('transporteur', 'Transporteur non identifiée.');
                    }
                }

                if(!$this->hasError()) {
                    $em = new EntityManager;
                    $choixTransporter = (new ChoixTransporteurRepository)->findOneBy(['commande' => $commande->getId(), 'transporteur' => $transporteur->getId()]);
                    if(!$choixTransporter) {
                        $choixTransporteur = new ChoixTransporteur([
                            'commande' => $commande->getId(),
                            'transporteur' => $transporteur->getId(),
                            'date_reception' => date('Y-m-d H:i:s')
                        ]);
                        $em->add($choixTransporteur);
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
                    $commande = (new CommandeRepository)->findOneBy(['id' => $post['accept_cmd']['commande']]);
                    if($commande) {
                        $em = new EntityManager;
                        $commande->setValide(1);
                        $em->update($commande);

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
                    $commande = (new CommandeRepository)->findOneBy(['id' => $post['cancel_cmd']['commande']]);
                    if($commande) {
                        $em = new EntityManager;
                        $commande->setValide(-1);
                        $em->update($commande);

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
                    $commande = (new CommandeRepository)->findOneBy(['id' => $form['commande']]);
                    if(null == $commande) {
                        $this->addError('commande', 'Commande non identifiée.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
            if(!$this->hasError()) {
                $em = new EntityManager;
                $commande->setArchive(1);
                $commande->setDateArchive(date('Y-m-d'));
                $em->update($commande);

                $this->setShowNotification(true);
                $this->setNotificationColor('bg-success');
                $this->addNotification('Commande archivée avec succès.');
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
                        $prothese = (new ProtheseRepository)->findOneBy(['id' => $pro]);
                        if(!$prothese) {
                            $this->addError('prothese', 'Prothèse non identifiée.');
                        } else {
                            $protheses[] = [
                                'prothese' => $prothese->getId(),
                                'cas_num' => $prothese->getNumero(),
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
                    $data['dentiste'] = $dentiste->getId();
                    $em = new EntityManager;
                    $commande = new Commande($data);
                    $em->add($commande);

                    foreach($protheses as $pro) {
                        $pro['commande'] = $commande->getId();
                        $pro['nom_patient'] = $commande->getUsernamePatient();
                        $choixProthese = new ChoixProthese($pro);
                        $em->add($choixProthese);
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
                    $commande = (new CommandeRepository)->findOneBy(['dentiste' => $dentiste->getId(), 'valide' => 1, 'slug' => $form['command']]);
                    if($commande) {
                        if($form['v'] == 2) {
                            $commande->setLivraison(2);
                            $commande->setDateLivraison(date('Y-m-d'));
                        } elseif($form['v'] == -1) {
                            $commande->setLivraison(-1);
                        }
                        $em = new EntityManager;
                        $em->update($commande);
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
