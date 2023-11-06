<?php
namespace src\Router;

use src\Vendor\Routes;
use src\Vendor\Security;
use src\Entity\Categorie;
use src\Entity\SousCategorie;
use src\Vendor\EntityManager;
use src\Vendor\GenerateRoutes;
use src\Services\MailerService;
use src\Services\HTMLMailService;
use src\Repository\UserRepository;
use src\Repository\AdminRepository;
use src\Repository\CategorieRepository;
use src\Repository\PrestationRepository;
use src\Repository\SousCategorieRepository;

clearstatcache();

function dump($data)
{
    null != $data ? var_dump($data) : '';
}
function dd($data, $a = null, $b = null, $c = null, $d = null, $e = null, $f = null, $g = null, $h = null, $i = null)
{
    dump($data);dump($a);dump($b);dump($c);dump($d);dump($e);dump($f);dump($g);dump($h);dump($i);
    die();
}

class RouterR extends Security
{
    /**
     * @var bool
     */
    private $show_notification;

    /**
     * @var string
     */
    private $notification_color;

    /**
     * @var array
     */
    private $notification;

    /**
     * @var array
     */
    private $error;

    /**
     * @var Routes
     */
    private $routes;

    public function __construct()
    {
        (new GenerateRoutes)->routes();
        $this->routes = new Routes;
        $this->show_notification = false;
        $this->notification_color = 'btn-white';
        $this->notification = [];
        $this->error = [];
    }

    public function getRoutes(): Routes
    {
        return $this->routes;
    }

    /**
     * key peut prendre les valeur: success, error, warning, notice
     */
    public function setNotification($key, $text)
    {
        $this->notification[$key] = $text;

        return $this;
    }

    public function getNotification(): array
    {
        return $this->notification;
    }

    public function showNotification()
    {
        return $this->show_notification;
    }

    public function getNotificationColor()
    {
        return $this->notification_color;
    }

    public function addError(?string $key, string $error)
    {
        if(null === $key) {
            $this->error[] = $error;
        } else {
            $this->error[$key] = $error;
        }
        return $this;
    }

    public function getError(): array
    {
        return $this->error;
    }

    public function hasError(): bool
    {
        return !empty($this->getError());
    }

    public function containError($key)
    {
        return array_key_exists($key, $this->getError());
    }

    public function errorHTML2($name)
    {
        if(!empty($this->getError())) {
            $errors = $this->getError();
            if(isset($errors[$name])) {
                echo '<div class="error text-danger" id="error_step0">
                        <span class="badge badge-danger text-uppercase">error</span> '. $errors[$name] .'
                    </div>';
            }
        }
    }

    public function getValPost($keys)
    {
        $value = '';
        foreach($keys as $id => $key) {
            if($id == 0) {
                if(isset($_POST[$key])) {
                    $value = $_POST[$key];
                }
            } else {
                if(isset($value[$key])) {
                    $value = $value[$key];
                }
            }
        }
        return $value;
    }

    public function setLastPath(string $path, array $param = null): self
    {
        $_SESSION['last_path'] = $this->routes->path($path);

        if(null !== $param && !empty($param)) {
            $count = count($param) - 1;
            $ind = 0;
            $_SESSION['last_path'] .= '?';
            foreach($param as $k => $v) {
                $_SESSION['last_path'] .= $k . '=' . $v;
                if($ind < $count) {
                    $_SESSION['last_path'] .= '&amp;';
                }
                $ind++;
            }
        }

        return $this;
    }

    public function getLastPath(): ?string
    {
        return isset($_SESSION['last_path']) ? $_SESSION['last_path'] : null;
    }

    public function removeLastPath(): self
    {
        if(isset($_SESSION['last_path'])) {
            unset($_SESSION['last_path']);
        }
        return $this;
    }

    /**
     * @param string $value: la valeur dont on veut vérifier l'exactitude
     * @param string $csrf: le csrf envoyé par le formulaire 
     */
    public function isCsrfValidate($value, $csrf)
    {
        return password_verify($value, $csrf);
    }

    private function isValidateEmail(string $email)
    {
        $email = htmlspecialchars($email);
        if(!empty($email)) 
        {
            if(!preg_match('/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $email)) 
            {
                $this->addError('email', 'Adresse email non valide.');
            } else {
                if(isset($_POST['edit_profil'])) {
                    $admin = (new AdminRepository)->findOneBy(['email' => $email]);
                    if($admin && strtolower($admin->getEmail()) !== strtolower($email)) {
                        $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
                    }
                } else {
                    if((new AdminRepository)->findOneBy(['email' => $email])) {
                        $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
                    } else {
                        // $_SESSION['registration']['email'] = $email;
                    }                    
                }
            }
        } else {
            $this->addError('email', 'Email requis.');
        }
    }

    private function isValidatePassword($password, $confirmation_password)
    {
        if(!empty($password)) {
            // $password = htmlspecialchars($password);
            if(!preg_match('/(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[0-9])(?=\S*[\W])/', $password) || strlen($password) < 8) {
                $this->addError('password', 'Doit contenir les carctères a-z, A-Z, 0-9 et caractères spéciaux(+,=,@,...)');
            } else {
                if(!empty($confirmation_password)) {
                    if($password !== $confirmation_password) {
                        $this->addError('confirmation_password', 'Confirmation Mot de passe incorrect.');   
                    } else {
                        $_SESSION['registration']['password'] = $password;
                    }
                } else {
                    $this->addError('confirmation_password', 'Confirmation Mot de passe requise.');  
                }
            }
        } else {
            $this->addError('password', 'Mot de passe requis');
        }
    }

    private function isValidateTexte($texte, $str)
    {
        if(empty(trim($texte))) {
            $this->addError($str, 'Please enter your ' . $str . '.');
        }
    }

    private function isValidatePhone($ph)
    {
        if(empty($ph)) {
            $this->addError('phone', 'Est requis.');
        } else {
            // if(!preg_match('#^(\+|00){1}[0-9]+#i', $ph))
            if(!preg_match('#[a-z]*#i', $ph)) {
                $this->addError('phone', 'Format invalide (Ex: +14151234567)');
            }
        }
    }

    public function adminSignin($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('log-in', $post['csrf'])) 
        {
            $data = $post['admin'];

            if(empty($data['email'])) {
                $this->addError('email', 'Email requis.');
            } else {
                $email = htmlspecialchars($data['email']);
            }

            if(empty($data['password'])) {
                $this->addError('password', 'Mot de passe requis.');
            } else {
                $password = $data['password']; //htmlspecialchars($data['password']);
                if(isset($email)) {
                    $admin = (new AdminRepository)->findOneBy(['email' => $email]);
                    if($admin) {
                        if(password_verify($password, $admin->getPassword())) {
                            $_SESSION['admin'] = serialize($admin);
                            if(isset($$data['remember'])) {
                                setcookie('remember_session', serialize(['email' => $email, 'pswd' => $password]), time() + 365*24*3600, '/', null, false, true);
                            } else if(isset($_COOKIE['remember_session'])){
                                setcookie('remember_session', '', time(), '/', null, false, true);
                            }
                            header('Location:' . $this->getRoutes()->path('dashboard'));
                        } else {
                            $this->show_notification = true;
                            $this->notification_color = 'btn-danger';
                            $this->setNotification('error', 'Mauvaise combinaison Email et Mot de passe');
                            $this->addError('error', 'Mot de passe erroné.');
                        }
                    } else {
                        $this->show_notification = true;
                        $this->notification_color = 'btn-danger';
                        $this->setNotification('error', 'Mauvaise combinaison Email et Mot de passe');
                    }
                }
            }
        } else {
            $this->addError('csrf', 'csrf non validé.');
        }

        // if(empty($this->getError())) {
        //     if(!isset($post['async']))
        //         header('location:' . $this->getLastPath());
        // }
    }

    public function addCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('new-categorie', $post['csrf']))
            {
                if(empty($post['categorie']['nom'])) {
                    $this->addError('categorie', 'Veuillez indiquer le nom de la catégorie.');
                } else {
                    $categorie = (new CategorieRepository)->findOneBy(['nom' => $post['categorie']['nom']]);
                    if($categorie) {
                        if($categorie->getDel() === 1) {
                            $this->addError('categorie', 'Cette catégorie existe déjà. Veuillez la restaurer dans la table des catégories supprimées.');
                        } else {
                            $this->addError('categorie', 'Cette catégorie existe déjà.');
                        }
                    } else {
                        $categorie = new Categorie(['nom' => $post['categorie']['nom'], 'admin' => $admin->getId()]);
                        $em = new EntityManager;
                        $em->add($categorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('categorie', 'Catégorie créée avec succès.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function editCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('edit-categorie', $post['csrf']))
            {
                if(empty($post['categorie']['id'])) {
                    $this->addError('categorie', 'Erreur non identifiée survenue.');
                } else {
                    if(empty($post['categorie']['nom'])) {
                        $this->addError('edit_categorie', 'Veuillez indiquer le nom de la catégorie.');
                    } else {
                        $categorie = (new CategorieRepository)->findOneBy(['id' => $post['categorie']['id']]);
                        if($categorie) {
                            $_categorie = (new CategorieRepository)->findOneBy(['nom' => $post['categorie']['nom']]);
                            if($_categorie && $_categorie->getId() !== $categorie->getId()) {
                                $this->addError('edit_categorie', 'Cette catégorie existe déjà.');
                            }
                        } else {
                            $this->addError('edit_categorie', 'Erreur non identifiée survenue.');
                        }
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }

            if(!$this->hasError()) {
                $categorie->setNom($post['categorie']['nom']);
                $em = new EntityManager;
                $em->update($categorie);
                $this->show_notification = true;
                $this->notification_color = 'btn-success';
                $this->setNotification('categorie', 'Catégorie éditée avec succès.');
            }
        }
    }

    public function delCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('del-categorie', $post['csrf']))
            {
                if(empty($post['categorie']['id'])) {
                    $this->addError('categorie', 'Erreur non identifiée survenue.');
                } else {
                    $categorie = (new CategorieRepository)->findOneBy(['id' => $post['categorie']['id']]);
                    if($categorie) {
                        $categorie->setDel(1);
                        $em = new EntityManager;
                        $em->update($categorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('categorie', 'Catégorie supprimée avec succès.');
                    } else {
                        $this->addError('categorie', 'Catégorie non trouvée.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function restaureCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('restaure-categorie', $post['csrf']))
            {
                if(empty($post['categorie']['id'])) {
                    $this->addError('categorie', 'Erreur non identifiée survenue.');
                } else {
                    $categorie = (new CategorieRepository)->findOneBy(['id' => $post['categorie']['id']]);
                    if($categorie) {
                        $categorie->setDel(0);
                        $em = new EntityManager;
                        $em->update($categorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('categorie', 'Catégorie restaurée avec succès.');
                    } else {
                        $this->addError('categorie', 'Catégorie non trouvée.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function addSubCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('new-souscategorie', $post['csrf']))
            {
                if(empty($post['sous_categorie']['nom'])) {
                    $this->addError('sous_categorie', 'Veuillez indiquer le nom de la sous-catégorie.');
                } else {
                    $sousCategorie = (new SousCategorieRepository)->findOneBy(['nom' => $post['sous_categorie']['nom']]);
                    if($sousCategorie) {
                        if($sousCategorie->getDel() === 1) {
                            $this->addError('sous_categorie', 'Cette sous-catégorie existe déjà. Veuillez la restaurer dans la table des sous-catégories supprimées.');
                        } else {
                            $this->addError('sous_categorie', 'Cette sous-catégorie existe déjà.');
                        }
                    } else {
                        $sousCategorie = new SousCategorie(['nom' => $post['sous_categorie']['nom'], 'admin' => $admin->getId()]);
                        $em = new EntityManager;
                        $em->add($sousCategorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('sous_categorie', 'Sous-Catégorie créée avec succès.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function editSubCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('edit-souscategorie', $post['csrf']))
            {
                if(empty($post['sous_categorie']['id'])) {
                    $this->addError('sous_categorie', 'Erreur non identifiée survenue.');
                } else {
                    if(empty($post['sous_categorie']['nom'])) {
                        $this->addError('edit_sous_categorie', 'Veuillez indiquer le nom de la sous-catégorie.');
                    } else {
                        $sousCategorie = (new SousCategorieRepository)->findOneBy(['id' => $post['sous_categorie']['id']]);
                        if($sousCategorie) {
                            $sous_categorie = (new SousCategorieRepository)->findOneBy(['nom' => $post['sous_categorie']['nom']]);
                            if($sous_categorie && $sous_categorie->getId() !== $sousCategorie->getId()) {
                                $this->addError('edit_sous_categorie', 'Cette sous-catégorie existe déjà.');
                            }
                        } else {
                            $this->addError('edit_sous_categorie', 'Erreur non identifiée survenue.');
                        }
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }

            if(!$this->hasError()) {
                $sousCategorie->setNom($post['sous_categorie']['nom']);
                $em = new EntityManager;
                $em->update($sousCategorie);
                $this->show_notification = true;
                $this->notification_color = 'btn-success';
                $this->setNotification('sous_categorie', 'Sous-catégorie éditée avec succès.');
            }
        }
    }

    public function delSubCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('del-souscategorie', $post['csrf']))
            {
                if(empty($post['sous_categorie']['id'])) {
                    $this->addError('sous_categorie', 'Erreur non identifiée survenue.');
                } else {
                    $sousCategorie = (new SousCategorieRepository)->findOneBy(['id' => $post['sous_categorie']['id']]);
                    if($sousCategorie) {
                        $sousCategorie->setDel(1);
                        $em = new EntityManager;
                        $em->update($sousCategorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('sous_categorie', 'Sous-Catégorie supprimée avec succès.');
                    } else {
                        $this->addError('sous_categorie', 'Sous-Catégorie non trouvée.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function restaureSubCategorie($post)
    {
        if($admin = $this->getAdmin()) 
        {
            if(isset($post['csrf']) && $this->isCsrfValidate('restaure-souscategorie', $post['csrf']))
            {
                if(empty($post['sous_categorie']['id'])) {
                    $this->addError('sous_categorie', 'Erreur non identifiée survenue.');
                } else {
                    $sousCategorie = (new SousCategorieRepository)->findOneBy(['id' => $post['sous_categorie']['id']]);
                    if($sousCategorie) {
                        $sousCategorie->setDel(0);
                        $em = new EntityManager;
                        $em->update($sousCategorie);
                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('sous_categorie', 'Sous-Catégorie restaurée avec succès.');
                    } else {
                        $this->addError('sous_categorie', 'Sous-Catégorie non trouvée.');
                    }
                }
            } else {
                $this->addError('csrf', 'Csrf non validé');
            }
        }
    }

    public function editStatusPrestation($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($post['csrf']) && $this->isCsrfValidate('prestation', $post['csrf']))
            {
                $prestation = (new PrestationRepository)->findOneBy(['slug' => htmlspecialchars($post['prestation'])]);
                if($prestation) {
                    $em = new EntityManager;
                    if(isset($post['ok_prestation'])) {
                        $prestation->setStatus(1);
                    } elseif(isset($post['cancel_prestation'])) {
                        $prestation->setStatus(-1);
                    }
                    $em->update($prestation);
                    $this->show_notification = true;
                    $this->notification_color = 'btn-success';
                    $this->setNotification('success', 'Action exécutée avec succès.');

                    $message = (new HTMLMailService(['prestation' => $prestation]))->getEditStatusPrestationMessage();
                    $mailerService = new MailerService($prestation->getUser()->getEmail(), 'Validation - Prestation', $message);
                    $mailerService->send();
                } else {
                    $this->show_notification = true;
                    $this->addError('error', '');
                }
            } else {
                $this->addError('csrf', 'Csrf non valide');
            }
        }
    }

    /**
     * edit profil de l'admin
     */
    public function editProfilAdmin($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($_POST['csrf']) && $this->isCsrfValidate('edit-profil', $_POST['csrf'])) 
            {
                $profil = $_POST['edit_profil'];
                $this->isValidateEmail(htmlspecialchars($profil['email']));
                // $this->isValidateNom($profil['nom']);
            } else {
                $this->show_notification = true;
                $this->notification_color = 'btn-danger';
                $this->setNotification('error', 'Csrf non validé');
                $this->addError('csrf', 'csrf non validé.');
            }

            if(!$this->hasError()) {
                $admin = $this->getAdmin();
                $admin->setEmail(htmlspecialchars($profil['email']));
                $em = new EntityManager;
                $em->update($admin);
                $_SESSION['admin'] = serialize($admin);
                $this->show_notification = true;
                $this->notification_color = 'btn-success';
                $this->setNotification('profil', 'Votre profil a été mis à jour.');
            }
        }
    }

    /**
     * edit password de l'admin
     */
    public function editPasswordAdmin($post)
    {
        if($admin = $this->getAdmin()) {
            if(isset($_POST['csrf']) && $this->isCsrfValidate('edit-password', $_POST['csrf'])) 
            {
                $pass = $post['edit_password'];
                if($this->isCsrfValidate($pass['oldpass'], (new AdminRepository)->findOneBy(['email' => $admin->getEmail()])->getPassword())) {
                    $this->isValidatePassword($pass['password'], $pass['confirmation_password']);
                } else {
                    $this->addError('oldpass', 'Erreur mot de passe.');
                }
            }
            if(!$this->hasError()) {
                $admin = $this->getAdmin();
                $admin->setPassword($pass['password']);
                $em = new EntityManager;
                $em->updatePassword($admin);
                $_SESSION['admin'] = serialize($admin);
                $this->show_notification = true;
                $this->notification_color = 'btn-success';
                $this->setNotification('password', 'Votre mot de passe a été mis à jour.');
            }
        }
    }

    private function passwordForgetAdmin($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('forgot-password', $post['csrf'])) {
            $psw_f = $post['forgot'];
            if(!empty($psw_f['email'])) 
            {
                $email = htmlspecialchars($psw_f['email']);
                if(!preg_match('/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $email)) 
                {
                    $this->addError('email', 'Adresse e-mail non valide.');
                } else {
                    $admin = (new AdminRepository)->findOneBy(['email' => $email]);
                    if($admin) {
                        $admin->setToken(md5(time() . $admin->getEmail()));
                        $admin->setExpireToken(strtotime((new \DateTime())->modify('+72hours')->format('Y-m-d H:i:s')));
                        $em = new EntityManager;
                        $em->update($admin);
            
                        // Envoie de mail au client
                        $message = (new HTMLMailService(['admin' => $admin, 'route' => $this->getRoutes()->path('admin_password_forgot')]))->getResetPasswordMessage();
                        $mailerService = new MailerService($admin->getEmail(), 'REINITIALISATION DE MOT DE PASSE', $message);
                        $mailerService->send();

                        $this->show_notification = true;
                        $this->notification_color = 'btn-success';
                        $this->setNotification('forgot', 'Félicitation! <br>Un mail vous a été envoyé. Veuillez consulter votre boîte de messagerie.');
                    } else {
                        $this->addError('email', 'Compte non identifié');
                    }
                }
            } else {
                $this->addError('email', 'Email requis.');
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }
    }

    public function isAuthentifiedTokenAdmin($get)
    {
        $email = htmlspecialchars($get['user']);
        $_token = htmlspecialchars($get['token']);
        $admin = (new AdminRepository)->findOneBy(['email' => $email]);
        if($admin) {
            if($token = $admin->getToken()) {
                if($token === $_token) {
                    $em = new EntityManager;
                    if($admin->isValidToken()) {
                        $_SESSION['_email'] = $admin->getEmail();
                        header('Location:' . $this->getRoutes()->path('admin_password_forgot'));
                    } else {
                        $admin->setToken(null);
                        $admin->setExpireToken(null);
                        $em->update($admin);

                        // NOTIFICATION
                        $this->show_notification = true;
                        $this->notification_color = 'btn-warning';
                        $this->setNotification('error', 'Lien expiré. <br>Veuillez reprendre la procédure. Merci.');
                    }
                } else {
                    $this->show_notification = true;
                    $this->notification_color = 'btn-danger';
                    $this->setNotification('error', 'Vous avez utilisé un mauvais Lien.');
                }
            } else {
                $this->show_notification = true;
                $this->notification_color = 'btn-danger';
                $this->setNotification('error', 'Lien expiré. <br>Veuillez reprendre la procédure. Merci.');
            }
        }
        return false;
    }

    private function resetPasswordAdmin($post)
    {
        if(isset($post['csrf']) && $this->isCsrfValidate('reset-password', $post['csrf'])) {
            $reset = $post['reset_psw'];
            if(!empty($_SESSION['_email'])) {
                $admin = (new AdminRepository)->findOneBy(['email' => $_SESSION['_email']]);
                if($admin) {
                    if(isset($reset['password']) && isset($reset['confirmation_password'])) {
                        $this->isValidatePassword($reset['password'], $reset['confirmation_password']);
                    }
                } else {
                    $this->show_notification = true;
                    $this->notification_color = 'btn-danger';
                    $this->setNotification('error', 'Compte non identifié');
                    $this->addError('email', 'Compte non identifié');
                }
            } else {
                $this->show_notification = true;
                $this->notification_color = 'btn-danger';
                $this->setNotification('error', 'Erreur non identifiée survenue. Veuillez reprendre la procédure.');
                $this->addError('error', 'Erreur non identifiée survenue. Veuillez reprendre la procédure.');
            }
        } else {
            $this->addError('csrf', 'Csrf non validé');
        }

        if(!$this->hasError()) {
            if(!empty($admin)) {
                $em = new EntityManager;
                $admin->setToken(null);
                $admin->setExpireToken(null);
                // $em->update($admin);
                $admin->setPassword($reset['password']);
                $em->updatePassword($admin);

                unset($_SESSION['_email']);
                $this->show_notification = true;
                $this->notification_color = 'btn-success';
                $this->setNotification('success', 'Votre mot de passe a été modifié avec succès.<br>Vous pouvez vous connecter avec votre nouveau mot de passe.');
            } else {
                $this->addError('email', 'Email non identifié.');
            }
        }
    }

    public function request()
    {
        if(isset($_POST['admin'])) {
            $this->adminSignin($_POST);
        }

        if(isset($_POST['prestation'])) {
            $this->editStatusPrestation($_POST);
        }

        if(isset($_POST['edit_profil'])) {
            $this->editProfilAdmin($_POST);
        }

        if(isset($_POST['edit_password'])) {
            $this->editPasswordAdmin($_POST);
        }

        if(isset($_POST['forgot'])) {
            $this->passwordForgetAdmin($_POST);
        }

        if(isset($_POST['reset_psw'])) {
            $this->resetPasswordAdmin($_POST);
        }

        if(isset($_POST['categorie'])) {
            if(isset($_POST['categorie']['add'])) {
                $this->addCategorie($_POST);
            } elseif(isset($_POST['categorie']['edit'])) {
                $this->editCategorie($_POST);
            } elseif(isset($_POST['categorie']['del'])) {
                $this->delCategorie($_POST);
            } elseif(isset($_POST['categorie']['restaure'])) {
                $this->restaureCategorie($_POST);
            }
        }

        if(isset($_POST['sous_categorie'])) {
            if(isset($_POST['sous_categorie']['add'])) {
                $this->addSubCategorie($_POST);
            } elseif(isset($_POST['sous_categorie']['edit'])) {
                $this->editSubCategorie($_POST);
            } elseif(isset($_POST['sous_categorie']['del'])) {
                $this->delSubCategorie($_POST);
            } elseif(isset($_POST['sous_categorie']['restaure'])) {
                $this->restaureSubCategorie($_POST);
            }
        }
    }

    private function key_exists($post, $key, $index = null)
    {
        if(empty($post[$key])) {
            $this->addError(null == $index ? $key : $index, 'is required.');
        } else {
            return true;
        }
        return;
    }

    public function sign_out()
    {
        session_destroy();

        header('Location:' . $this->getRoutes()->path('admin_login'));
    }

    public function returnLastPathIfConnect()
    {
        if($this->getAdmin()) {
            header('Location: ' . $this->getLastPath());
        }
    }

    public function isConnected()
    {
        if(!$this->getAdmin()) {
            header('Location: ' . $this->routes->path('admin_login'));
        }
    }

    public function isLogin()
    {
        if($this->getAdmin()) {
            header('Location:' . $this->routes->path('home'));
        }
    }

    public function formatText(string $texte, int $length)
    {
        if(strlen($texte) > $length) {
            $str = substr($texte, 0, $length - 3) . '...';
        } else {
            $str = substr($texte, 0, $length);
        }
        return $str;
    }

    public function getSrc($object, $class = 'user', $is_chat = false)
    {
        $path = '';
        if(method_exists($object, 'getImage')) {
            if(is_null($object->getImage()) || '' === $object->getImage()) {
                return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person w-100 h-100" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                        </svg>';
                // return '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-fill w-100 h-100 text-white" viewBox="0 0 16 16">
                //             <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                //         </svg>';
            } else {
                return '<img src="./assets/images/faces/' . $object->getImage() . '" alt="" class="w-100 h-100" />';
            }
        }
    }

    public function getAvatar (string $path, ?string $image)
    {
        if(null == $image) {
            return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person w-100 h-100" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>';
            return '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person-fill w-100 h-100" viewBox="0 0 16 16" style="color:#eee;">
                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>';
        } else {
            return '<img src="' . $this->getExternalSource($path, $image) . '" alt="" class="w-100 h-100" />';
        }
    }

    public function getExternalSource(string $path, $e)
    {
        $inline = false;
        if($inline) {
            return 'http://lawyours.link/assets/' . $path . '/' . $e;
        } else {
            return './assets/' . $path . '/' . $e;
        }
    }

    public function getValue(?string $v)
    {
        return is_null($v) || '' === $v ? '--' : $v;
    }

    public function getDate(string $date)
    {
        $date = (new \DateTime($date))->format('D d M, Y');
        return str_replace(['Mond', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche', 'Jan', 'Fev', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Déc'], $date);
    }

    public function getDateDiff($current_time, $time2)
    {
        $str = '';
        $firstDateTime = null;
        $lastDateTime = null;

        $method = 1;

        if(is_int($current_time)) {
            $firstDateTime = new \DateTime(date('Y-m-d H:i:s', $current_time));
        } else {
            $firstDateTime = new \DateTime($current_time);
        }

        if(is_int($time2)) {
            $lastDateTime = new \DateTime(date('Y-m-d H:i:s', $time2));
        } else {
            $lastDateTime = new \DateTime($time2);
        }

        if($method == 1) {
            $year1 = (int)$firstDateTime->format('Y');
            $month1 = (int)$firstDateTime->format('m');
            $day1 = (int)$firstDateTime->format('d');
    
            $year2 = (int)$lastDateTime->format('Y');
            $month2 = (int)$lastDateTime->format('m');
            $day2 = (int)$lastDateTime->format('d');
    
            if($year1 == $year2) {
                if($month1 == $month2) {
                    if($day1 == $day2) {
                        $str = 'Today';
                    } else {
                        if($day2 + 1 == $day1) {
                            $str = 'Yesterday';
                        } else {
                            $str = (new \DateTime($time2))->format('D, d M');
                        }
                    }
                } else {
                    if($month2 + 1 == $month1) {
                        if($day1 == 1) {
                            $str = 'Yesterday';
                        } else {
                            $str = (new \DateTime($time2))->format('D, d M');
                        }
                    } else {
                        $str = (new \DateTime($time2))->format('D, d M');
                    }
                }
            } else {
                if($year2 + 1 == $year1) {
                    if($month2 == 1) {
                        if($day2 == 1) {
                            $str = 'Yesterday';
                        } else {
                            $str = (new \DateTime($time2))->format('D, d M Y');   
                        }
                    } else {
                        $str = (new \DateTime($time2))->format('D, d M Y');
                    }
                } else {
                    $str = (new \DateTime($time2))->format('D, d M Y');
                }
            }
        } elseif($method == 2) {
            $dateDiff = date_diff($firstDateTime, $lastDateTime);

            if($dateDiff->y !== 0) {
                $str = date('Y-m-d', $time2);
            } else if($dateDiff->m !== 0) {
                $str = (new \DateTime($time2))->format('D, d M Y');
            } else if($dateDiff->d !== 0) {
                if($dateDiff->y === 1) {
                    $str = 'Yesterday';
                } else {
                    $str = (new \DateTime($time2))->format('D, d M');
                }
            } else {
                $str = 'Today';
            }
        }

        return $str;
    }
}
