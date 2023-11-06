<?php
namespace src\Controller;

use src\Entity\Dentiste;
use src\Vendor\Security;
use src\Services\FileService;
use src\Vendor\EntityManager;
use src\Repository\DentisteRepository;

class DentisteController extends Security
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
                $user = (new DentisteRepository)->findOneBy(['email' => $email]);
                if(isset($_POST['upd_compte_dentiste'])) {
                    $user_connected = $this->getDentiste();
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

    private function isValidatePassword($password, $confirmation_password, $label_password = 'password', $label_confirmation = 'confirmation_password')
    {
        if(!empty($password)) {
            if(!preg_match('/(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[0-9])(?=\S*[\W])/', $password) || strlen($password) < 8) {
                $this->addError($label_password, 'Doit contenir les carctères a-z, A-Z, 0-9 et caractères spéciaux(+,=,@,...)');
                return false;
            } else {
                if(!empty($confirmation_password)) {
                    if($password !== $confirmation_password) {
                        $this->addError($label_confirmation, 'Confirmation Mot de passe incorrect.');
                        return false;
                    } else {
                        // $_SESSION['registration']['password'] = $password;
                    }
                } else {
                    $this->addError($label_confirmation, 'Confirmation Mot de passe requise.');  
                    return false;
                }
            }
        } else {
            $this->addError($label_password, 'Mot de passe requis');
            return false;
        }
        return true;
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

    private function baseCreateAccount($form)
    {
        $data = [];
    
        if(!$this->isBlank($form, 'nom', 'nom')) {
            $data['nom'] = trim($form['nom']);
        }
        if(!$this->isBlank($form, 'prenom', 'prenom')) {
            $data['prenom'] = trim($form['prenom']);
        }
        if(!$this->isBlank($form, 'cabinet', 'cabinet')) {
            $data['cabinet'] = trim($form['cabinet']);
        }
        if(!$this->isBlank($form, 'adresse', 'adresse')) {
            $data['adresse'] = trim($form['adresse']);
        }
        if(!$this->isBlank($form, 'email', 'email')) {
            if($this->isValidateEmail($form['email'], null)) {
                $data['email'] = trim($form['email']);
            }
        }
        if(!$this->isBlank($form, 'password', 'password')) {
            if($this->isValidatePassword($form['password'], $form['confirmation_password'])) {
                $data['password'] = trim($form['password']);
            }
        }
        if(!$this->hasError()) {
            $em = new EntityManager;
            $dentiste = new Dentiste($data);
            $em->add($dentiste);

            return ['success' => true, 'user' => serialize($dentiste)];
        }
        return ['success' => false];
    }

    public function createDentisteAccount($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('admin-signup-dentiste', $post['csrf'])) {
                $form = $post['compte_dentiste'];
                $password = $form['password'];
                $response = $this->baseCreateAccount($form);
                if($response['success']) {
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Compte dentiste créer avec succès.<br>Mdp: ' . $password);
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function createAccount($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('dentiste-signup', $post['csrf'])) {
            $form = $post['my_compte_dentiste'];
            $response = $this->baseCreateAccount($form);
            if($response['success']) {
                $_SESSION['dentiste'] = $response['user'];
                header('Location: accueil.php');
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
    }

    public function updateAccount($post, $files)
    {
        if($user = $this->getDentiste()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('update-account-dentiste', $post['csrf'])) {
                $form = $post['upd_compte_dentiste'];
                $data = [];
    
                if(!$this->isBlank($form, 'nom', 'nom')) {
                    $data['nom'] = trim($form['nom']);
                }
                if(!$this->isBlank($form, 'prenom', 'prenom')) {
                    $data['prenom'] = trim($form['prenom']);
                }
                if(!$this->isBlank($form, 'cabinet', 'cabinet')) {
                    $data['cabinet'] = trim($form['cabinet']);
                }
                if(!$this->isBlank($form, 'adresse', 'adresse')) {
                    $data['adresse'] = trim($form['adresse']);
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
                    $user->hydrate($data);
                    $em->update($user);
                    $_SESSION['dentiste'] = serialize($user);
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Votre compte a été mis à jour avec succès');
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function updatePassword($post)
    {
        if($user = $this->getDentiste()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('update-password-dentiste', $post['csrf'])) {
                $form = $post['upd_password_dentiste'];

                if(!$this->isBlank($form, 'password', 'password')) {
                    $password_hash = (new DentisteRepository)->findOneBy(['id' => $user->getId()])->getPassword();
                    if($this->isCsrfValidate($form['password'], $password_hash)) {
                        $new_password = $form['new_password'];
                        $confirmation_new_password = $form['confirmation_password'];
                        
                        if($this->isValidatePassword($new_password, $confirmation_new_password, 'new_password', 'confirmation')) {
                            if(!$this->hasError()) {
                                $user->setPassword($new_password);
                                $em = new EntityManager;
                                $em->updatePassword($user);
                                
                                $this->setShowNotification(true);
                                $this->setNotificationColor('bg-success');
                                $this->addNotification('Votre mot de passe a été modifié avec succès.');
                            }
                        }
                    } else {
                        $this->addError('password', 'Mot de passe incorrect.');
                    }
                }
            }
        }
    }
}