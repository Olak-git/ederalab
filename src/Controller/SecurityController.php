<?php
namespace src\Controller;

use src\Vendor\DB;
use src\Vendor\Security;
use src\Vendor\EntityManager;
use src\Services\MailerService;
use src\Services\HTMLMailService;

class SecurityController extends Security
{
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
                $admin = $this->db->findOneBy("admin", ['active' => 1]);
                if($admin && $this->isCsrfValidate($post['pass'], $admin["identifiant"])) {
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
                $identifiant = $this->db->findOneBy("identifiant", ['code' => $form['identifiant']]);
                if($identifiant) {
                    $transporteur = $this->db->findOneBy("transporteur", ['id' => $identifiant["transporteur"]]);
                    if($transporteur && $this->isCsrfValidate($form['identifiant'], $transporteur["identifiant"])) {
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
                $user = $this->db->findOneBy("dentiste", ['email' => $email]);
                if($user) {
                    if(password_verify($password, $user["password"])) {                        
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
                $admin = $this->db->findOneBy("admin", ['email' => $form['email']]);
                if($admin) {
                    $em = new EntityManager;
                    $pswd = $this->generatePassword();
                    (new EntityManager)->update("admin", ["identifiant" => password_hash($pswd, 1)], $admin["id"]);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($admin["email"], 'Réinitialisation de l\'identifiant', $message);
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
                $transporteur = $this->db->findOneBy("transporteur", ['email' => $form['email']]);
                if($transporteur) {
                    $em = new EntityManager;
                    $pswd = $this->generatePassword();
                    
                    $em->update("transporteur", ["identifiant" => password_hash($pswd, 1)], $transporteur["id"]);
                    $em->update("identifiant", ["code" => $pswd], [
                        ['transporteur', "=", $transporteur["id"]]
                    ]);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($transporteur["email"], 'Réinitialisation de l\'identifiant', $message);
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
                $dentiste = $this->db->findOneBy("dentiste", ['email' => $form['email']]);
                if($dentiste) {
                    $pswd = $this->generatePassword();
                    (new EntityManager)->update("dentiste", ["password" => password_hash($pswd, 1)], $dentiste["id"]);

                    // Envoie de mail
                    $message = (new HTMLMailService(['psw' => $pswd]))->getResetPasswordForgetMessage();
                    $mailerService = new MailerService($dentiste["email"], 'Réinitialisation de mot de passe', $message);
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