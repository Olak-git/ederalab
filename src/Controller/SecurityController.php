<?php
namespace src\Controller;

use src\Vendor\Security;
use src\Vendor\EntityManager;
use src\Services\MailerService;
use src\Services\HTMLMailService;
use src\Repository\AdminRepository;
use src\Repository\DentisteRepository;
use src\Repository\IdentifiantRepository;
use src\Repository\TransporteurRepository;

class SecurityController extends Security
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

    public function adminSignIn($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('admin-signin', $post['csrf']))  {
            if(empty($post['pass'])) {
                $this->addError('pass', 'Est requis');
            } else {
                $admin = (new AdminRepository)->findOneBy(['active' => 1]);
                if($admin && $this->isCsrfValidate($post['pass'], $admin->getIdentifiant())) {
                    $_SESSION['admin'] = serialize($admin);
                } else {
                    $this->addError('pass', 'Compte non identifié');
                }
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
        if(!$this->hasError()) {
            header('Location:calendrier-de-planification-commande-recue.php');
        }
    }

    public function transporteurSignIn($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('transporteur-signin', $post['csrf']))  {
            $form = $post['signin_transporteur'];
            if(empty($form['identifiant'])) {
                $this->addError('identifiant', 'Est requis');
            } else {
                $identifiant = (new IdentifiantRepository)->findOneBy(['code' => $form['identifiant']]);
                if($identifiant) {
                    $transporteur = $identifiant->getTransporteur();
                    if($transporteur && $this->isCsrfValidate($form['identifiant'], $transporteur->getIdentifiant())) {
                        $_SESSION['transporteur'] = serialize($transporteur);
                        header('Location: accueil.php');
                    } else {
                        $this->addError('identifiant', 'Compte non identifié');
                    }
                } else {
                    $this->addError('identifiant', 'Compte non identifié');
                }
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
    }

    public function dentisteSignIn($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('dentiste-signin', $post['csrf'])) {
            $form = $post['dentiste_signin'];
            $email = $password = '';
            if(!$this->isBlank($form, 'email', 'email')) {
                $email = $form['email'];
            }
            if(!$this->isBlank($form, 'password', 'password')) {
                $password = $form['password'];
            }
            if(!$this->hasError()) {
                $user = (new DentisteRepository)->findOneBy(['email' => $email]);
                if($user) {
                    if(password_verify($password, $user->getPassword())) {
                        // $user->setConnecte(1);
                        // $user->setTimestamp(time());
                        // $user->setTimestamp(strtotime((new \DateTime())->format('Y-m-d H:i:s')));

                        // $em = new EntityManager;
                        // $em->update($user);
                        
                        $_SESSION['dentiste'] = serialize($user);

                        header('Location: accueil.php');

                    } else {
                        $this->addError('password', 'Mauvais mot de passe.');
                    }
                } else {
                    $this->addError('email', 'Compte non identifié.');
                }
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
    }

    // Admin
    public function resetAdminIdentifiant($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('reset-identifiant', $post['csrf'])) {
            $form = $post['admin_reset_identifiant'];
            if(!$this->isBlank($form, 'email', 'email')) {
                $user = (new AdminRepository)->findOneBy(['email' => $form['email']]);
                if($user) {
                    $em = new EntityManager;
                    $pswd = $this->generatePassword();

                    $user->setIdentifiant(password_hash($pswd, 1));
    
                    $em->update($user);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($user->getEmail(), 'Réinitialisation de l\'identifiant', $message);
                    $mailerService->send();
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Un nouvel identifiant vous a été généré. Veuillez consulter votre boîte email.<br>Merci.');
                } else {
                    $this->addError('email', 'Compte non identifié.');
                }
            }
        }
    }

    // Transporteur
    public function resetIdentifiant($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('reset-identifiant', $post['csrf'])) {
            $form = $post['collaborateur_reset_identifiant'];
            if(!$this->isBlank($form, 'email', 'email')) {
                $user = (new TransporteurRepository)->findOneBy(['email' => $form['email']]);
                if($user) {
                    $em = new EntityManager;
                    $pswd = $this->generatePassword();

                    $user->setIdentifiant(password_hash($pswd, 1));
    
                    $identifiant = $user->getEntityCode();
                    $identifiant->setCode($pswd);
    
                    $em->update($user);
                    $em->update($identifiant);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($user->getEmail(), 'Réinitialisation de l\'identifiant', $message);
                    $mailerService->send();
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Un nouvel identifiant vous a été généré. Veuillez consulter votre boîte email.<br>Merci.');
                } else {
                    $this->addError('email', 'Compte non identifié.');
                }
            }
        }
    }

    // Dentiste
    public function resetPassword($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('reset-password', $post['csrf'])) {
            $form = $post['dentiste_reset_password'];
            if(!$this->isBlank($form, 'email', 'email')) {
                $user = (new DentisteRepository)->findOneBy(['email' => $form['email']]);
                if($user) {
                    $pswd = $this->generatePassword();
                    $user->setPassword($pswd);
                    $em = new EntityManager;
                    $em->updatePassword($user);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($user->getEmail(), 'Réinitialisation de mot de passe', $message);
                    $mailerService->send();
    
                    $this->setShowNotification(true);
                    $this->setNotificationColor('bg-success');
                    $this->addNotification('Un nouveau mot de passe vous a été généré. Veuillez consulter votre boîte email.<br>Merci.');
                } else {
                    $this->addError('email', 'Compte non identifié.');
                }
            }
        }
    }

    private function generatePassword()
    {
        return substr(md5(password_hash(time(), 1)), 0, 6);
    }
}