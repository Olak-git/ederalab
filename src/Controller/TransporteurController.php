<?php
namespace src\Controller;

use src\Vendor\Security;
use src\Entity\Identifiant;
use src\Entity\Transporteur;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\Repository\IdentifiantRepository;
use src\Repository\TransporteurRepository;

class TransporteurController extends Security
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

    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    private function isValidatePhone($ph)
    {
        if(empty($ph)) {
            $this->addError('phone', 'Est requis.');
        } else {
            if(!preg_match('#^(\+){1}[0-9]+[ ]*#i', $ph))
            // if(!preg_match('#[a-z]*#i', $ph)) 
            {
                $this->addError('phone', 'Format invalide (Ex: +14151234567)');
            }
        }
    }

    private function isValidateEmail(string $email, $any = null)
    {
        $email = htmlspecialchars($email);
        if(!empty($email)) 
        {
            if(!preg_match('/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $email)) 
            {
                $this->addError('email', 'Adresse email non valide.');
                return false;
            } else {
                $user = (new TransporteurRepository)->findOneBy(['email' => $email]);
                if(isset($_POST['upd_compte_transporteur'])) {
                    $user_connected = $this->getTransporteur();
                    if($user && $user_connected && strtolower($user->getEmail()) !== strtolower($user_connected->getEmail())) {
                        $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
                    }
                } else {
                    if($user) {
                        $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
                    } else {
                        // $_SESSION['registration']['email'] = $email;
                    }                    
                }
            }
        } else {
            $this->addError('email', 'Email requis.');
            return false;
        }
        return true;
    }

    public function createTranspoter($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('new-transporter', $post['csrf']))
            {
                $form = $post['transporteur'];
                if(empty($form['identifiant'])) {
                    $this->addError('identifiant', 'Est requis');
                } else {
                    if(empty(trim($form['identifiant']))) {
                        $this->addError('identifiant', 'Est requis');
                    } else {
                        if(preg_match('#[ ]+#i', $form['identifiant'])) {
                            $this->addError('identifiant', 'L\'identifiant ne doit contenir aucun espace.');
                        } else {
                            $_transporteur = (new IdentifiantRepository)->findOneBy(['code' => $form['identifiant']]);
                            if($_transporteur) {
                                $this->addError('identifiant', 'Le compte <em>' . $form['identifiant'] . '</em> existe déjà pour un utilisateur. Veuillez le changer.');
                            }
                        }
                    }
                }

                if(!$this->isBlank($form, 'nom', 'nom')) {
                    $data['nom'] = $form['nom'];
                }
                if(!$this->isBlank($form, 'adresse', 'adresse')) {
                    $data['adresse'] = $form['adresse'];
                }
                if(!$this->isBlank($form, 'phone', 'phone')) {
                    $this->isValidatePhone($form['phone']);
                    $data['phone'] = $form['phone'];
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
            if(!$this->hasError()) {
                $em = new EntityManager;

                $nom_pre = explode(' ', $form['nom']);
                $nom = $nom_pre['0'];
                $prenom = trim(substr($form['nom'], strlen($nom), strlen($form['nom'])));

                $transporteur = new Transporteur([
                    'identifiant' => $form['identifiant'],
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'adresse' => $form['adresse'],
                    'phone' => $form['phone'],
                ]);
                $em->add($transporteur);

                $identifiant = new Identifiant([
                    'code' => $form['identifiant'],
                    'transporteur' => $transporteur->getId()
                ]);
                $em->add($identifiant);
            }
        } else {
            
        }
    }

    public function updateTranspoter($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('upd-transporter', $post['csrf']))
            {
                $form = $post['edit_transporteur'];
                $data = [];
                if(empty($form['id'])) {
                    $this->addError('error', 'Une erreur non identifiée est survenue. Veuillez reprendre.');
                } else {
                    $transporteur = (new TransporteurRepository)->findOneBy(['id' => $form['id']]);
                    if($transporteur) {
                        if(empty($form['identifiant'])) {
                            $this->addError('identifiant', 'Est requis');
                        } else {
                            if(empty(trim($form['identifiant']))) {
                                $this->addError('identifiant', 'Est requis');
                            } else {
                                if(preg_match('#[ ]+#i', $form['identifiant'])) {
                                    $this->addError('identifiant', 'L\'identifiant ne doit contenir aucun espace.');
                                } else {
                                    $identifiant = (new IdentifiantRepository)->findOneBy(['code' => $form['identifiant']]);
                                    if($identifiant && !$this->isCsrfValidate($identifiant->getCode(), $transporteur->getIdentifiant())) {
                                        $this->addError('identifiant', 'Le compte <em>' . $form['identifiant'] . '</em> existe déjà pour un utilisateur. Veuillez le changer.');
                                    }
                                }
                            }
                        }

                        if(!$this->isBlank($form, 'nom', 'nom')) {
                            $data['nom'] = $form['nom'];
                        }
                        if(!$this->isBlank($form, 'adresse', 'adresse')) {
                            $data['adresse'] = $form['adresse'];
                        }
                        if(!$this->isBlank($form, 'phone', 'phone')) {
                            $this->isValidatePhone($form['phone']);
                            $data['phone'] = $form['phone'];
                        }
                    } else {
                        $this->addError('error', 'Une erreur non identifiée est survenue. Veuillez reprendre.');
                    }
                }

            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
            if(!$this->hasError()) {
                $em = new EntityManager;

                $nom_pre = explode(' ', $form['nom']);
                $nom = $nom_pre['0'];
                $prenom = trim(substr($form['nom'], strlen($nom), strlen($form['nom'])));

                $transporteur->setIdentifiant(password_hash(trim($form['identifiant']), 1));
                $transporteur->setNom($nom);
                $transporteur->setPrenom($prenom);
                $transporteur->setAdresse($form['adresse']);
                $transporteur->setPhone($form['phone']);

                $identifiant = $transporteur->getEntityCode();
                $identifiant->setCode($form['identifiant']);

                $em->update($transporteur);
                $em->update($identifiant);
            }
        } else {
            $this->addError('error', 'Une erreur non identifiée est survenue. Veuillez reprendre.');
        }
    }

    public function createAccount($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('transporteur-signup', $post['csrf']))
        {
            $form = $post['transporteur_signup'];
            $data = [];
            if(empty($form['identifiant'])) {
                $this->addError('identifiant', 'Est requis');
            } else {
                if(empty(trim($form['identifiant']))) {
                    $this->addError('identifiant', 'Est requis');
                } else {
                    if(preg_match('#[ ]+#i', $form['identifiant'])) {
                        $this->addError('identifiant', 'L\'identifiant ne doit contenir aucun espace.');
                    } else {
                        $_transporteur = (new IdentifiantRepository)->findOneBy(['code' => $form['identifiant']]);
                        if($_transporteur) {
                            $this->addError('identifiant', 'Le compte <em>' . $form['identifiant'] . '</em> existe déjà pour un utilisateur. Veuillez le changer.');
                        } else {
                            $data['identifiant'] = $form['identifiant'];
                        }
                    }
                }
            }

            if(!$this->isBlank($form, 'nom', 'nom')) {
                $data['nom'] = $form['nom'];
            }
            if(!$this->isBlank($form, 'prenom', 'prenom')) {
                $data['prenom'] = $form['prenom'];
            }
            if(!$this->isBlank($form, 'email', 'email')) {
                if($this->isValidateEmail($form['email'])) {
                    $data['email'] = trim($form['email']);
                }
            }
            if(!$this->isBlank($form, 'adresse', 'adresse')) {
                $data['adresse'] = $form['adresse'];
            }
            // if(!$this->isBlank($form, 'phone', 'phone')) {
            //     $this->isValidatePhone($form['phone']);
            //     $data['phone'] = $form['phone'];
            // }

            if(!$this->hasError()) {
                $em = new EntityManager;
    
                $transporteur = new Transporteur($data);
                $em->add($transporteur);
    
                $identifiant = new Identifiant([
                    'code' => $form['identifiant'],
                    'transporteur' => $transporteur->getId(),
                ]);
                $em->add($identifiant);

                $_SESSION['transporteur'] = serialize($transporteur);
                header('Location: accueil.php');
            }
        } else {
            $this->addError('csrf', 'Csrf non valide.');
        }
    }

    public function updateAccount($post, $files)
    {
        if($transporteur = $this->getTransporteur()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('update-account-transporteur', $post['csrf'])){
                $form = $post['upd_compte_transporteur'];
                $data = [];
                if(!$this->isBlank($form, 'identifiant', 'identifiant')) {
                    if(preg_match('#[ ]+#i', $form['identifiant'])) {
                        $this->addError('identifiant', 'L\'identifiant ne doit contenir aucun espace.');
                    } else {
                        $identifiant = (new IdentifiantRepository)->findOneBy(['code' => $form['identifiant']]);
                        if($identifiant && !$this->isCsrfValidate($identifiant->getCode(), $transporteur->getIdentifiant())) {
                            $this->addError('identifiant', 'Le compte <em>' . $form['identifiant'] . '</em> existe déjà pour un utilisateur. Veuillez le changer.');
                        } else {
                            $data['identifiant'] = password_hash(trim($form['identifiant']), 1);
                        }
                    }
                }
                if(!$this->isBlank($form, 'nom', 'nom')) {
                    $data['nom'] = $form['nom'];
                }
                if(!$this->isBlank($form, 'prenom', 'prenom')) {
                    $data['prenom'] = $form['prenom'];
                }
                if(!$this->isBlank($form, 'adresse', 'adresse')) {
                    $data['adresse'] = $form['adresse'];
                }
                if(!$this->isBlank($form, 'phone', 'phone')) {
                    if(!preg_match('#^(\+){1}[0-9]+[ ]*#i', $form['phone'])){
                        $this->addError('phone', 'Format invalide (Ex: +14151234567)');
                    } else {
                        $data['phone'] = $form['phone'];
                    }
                }
                if(!$this->isBlank($form, 'email', 'email')) {
                    if($this->isValidateEmail($form['email'], null)) {
                        $data['email'] = trim($form['email']);
                    }
                }
                if(isset($files['avatar']) && $files['avatar']['name'] !== '') {
                    $fileService = new FileService($files['avatar'], 'image', 'images/avatars', true, 5, 'M', false);
                    if($fileService->saveFile()) {
                        $data['image'] = $fileService->getName();
                    } elseif($fileService->getError()) {
                        $this->addError('avatar', $fileService->getError());
                    }
                }

                if(!$this->hasError()) {
                    $em = new EntityManager;
                    $transporteur->hydrate($data);

                    $identifiant = $transporteur->getEntityCode();
                    $identifiant->setCode($form['identifiant']);
    
                    $em->update($transporteur);
                    $em->update($identifiant);

                    $_SESSION['transporteur'] = serialize($transporteur);
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Votre compte a été mis à jour avec succès');
                }
            } else {
                $this->addError('csrf', 'Csrf non valide.');
            }
        }
    }

    public function deleteTranspoter($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('del-transporter', $post['csrf']))
            {
                $form = $post['del_transporteur'];
                if(empty($form['id'])) {
                    $this->addError('error', 'Une erreur non identifiée est survenue. Veuillez reprendre.');
                } else {
                    $transporteur = (new TransporteurRepository)->findOneBy(['id' => $form['id']]);
                    if($transporteur) {
                        $em = new EntityManager;
                        $transporteur->setDel(1);
                        $em->update($transporteur);
                    }
                }
            }
        }
    }
}