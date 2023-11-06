<?php
namespace src\Router;

use src\Vendor\Routes;
use src\Vendor\Security;
use src\Vendor\GenerateRoutes;
use src\Entity\ChoixTransporteur;
use src\Controller\AdminController;
use src\Repository\AdminRepository;
use src\Controller\FactureController;
use src\Controller\MessageController;
use src\Controller\CommandeController;
use src\Controller\DentisteController;
use src\Controller\ProtheseController;
use src\Controller\SecurityController;
use src\Repository\CommandeRepository;
use src\Repository\DiscussionRepository;
use src\Controller\TransporteurController;
use src\Repository\ChoixTransporteurRepository;

clearstatcache();

class Router extends Security
{
    /**
     * @var Routes
     */
    private $routes;

    public function __construct()
    {
        parent::__construct();
        
        (new GenerateRoutes)->routes();
        $this->routes = new Routes;
    }

    public function getRoutes(): Routes
    {
        return $this->routes;
    }

    public function errorHTML2($name)
    {
        if($this->hasError()) {
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

    // private function isValidateEmail(string $email)
    // {
    //     $email = htmlspecialchars($email);
    //     if(!empty($email)) 
    //     {
    //         if(!preg_match('/^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i', $email)) 
    //         {
    //             $this->addError('email', 'Adresse email non valide.');
    //         } else {
    //             if(isset($_POST['edit_profil'])) {
    //                 $admin = (new AdminRepository)->findOneBy(['email' => $email]);
    //                 if($admin && strtolower($admin->getEmail()) !== strtolower($email)) {
    //                     $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
    //                 }
    //             } else {
    //                 if((new AdminRepository)->findOneBy(['email' => $email])) {
    //                     $this->addError('email', 'Un compte existe déjà avec cette adresse email. Veuillez en utiliser une autre.');
    //                 } else {
    //                     // $_SESSION['registration']['email'] = $email;
    //                 }                    
    //             }
    //         }
    //     } else {
    //         $this->addError('email', 'Email requis.');
    //     }
    // }

    // private function isValidatePassword($password, $confirmation_password)
    // {
    //     if(!empty($password)) {
    //         // $password = htmlspecialchars($password);
    //         if(!preg_match('/(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[0-9])(?=\S*[\W])/', $password) || strlen($password) < 8) {
    //             $this->addError('password', 'Doit contenir les carctères a-z, A-Z, 0-9 et caractères spéciaux(+,=,@,...)');
    //         } else {
    //             if(!empty($confirmation_password)) {
    //                 if($password !== $confirmation_password) {
    //                     $this->addError('confirmation_password', 'Confirmation Mot de passe incorrect.');   
    //                 } else {
    //                     $_SESSION['registration']['password'] = $password;
    //                 }
    //             } else {
    //                 $this->addError('confirmation_password', 'Confirmation Mot de passe requise.');  
    //             }
    //         }
    //     } else {
    //         $this->addError('password', 'Mot de passe requis');
    //     }
    // }

    // private function isValidateTexte($texte, $str)
    // {
    //     if(empty(trim($texte))) {
    //         $this->addError($str, 'Please enter your ' . $str . '.');
    //     }
    // }

    // private function isValidatePhone($ph)
    // {
    //     if(empty($ph)) {
    //         $this->addError('phone', 'Est requis.');
    //     } else {
    //         // if(!preg_match('#^(\+|00){1}[0-9]+#i', $ph))
    //         if(!preg_match('#[a-z]*#i', $ph)) {
    //             $this->addError('phone', 'Format invalide (Ex: +14151234567)');
    //         }
    //     }
    // }

    public function signout(string $user)
    {
        if($user == 'admin') {
            unset($_SESSION['admin']);
        }
        elseif($user == 'dentiste') {
            unset($_SESSION['dentiste']);
        }
        elseif($user == 'transporteur') {
            unset($_SESSION['transporteur']);
        }
        if(!$this->getAdmin() && !$this->getDentiste() && !$this->getTransporteur()) {
            session_destroy();
        }

        // header('Location: connexion.php');
    }

    public function returnLastPathIfConnect()
    {
        if($this->getAdmin()) {
            header('Location: ' . $this->getLastPath());
        }
    }

    public function adminIsConnected()
    {
        if(!$this->getAdmin()) {
            header('Location: connexion.php');
        }
    }

    public function transporteurIsConnected()
    {
        if(!$this->getTransporteur()) {
            header('Location: connexion.php');
        }
    }

    public function dentisteIsConnected()
    {
        if(!$this->getDentiste()) {
            header('Location: connexion.php');
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
            } else {
                return '<img src="./assets/images/faces/' . $object->getImage() . '" alt="" class="w-100 h-100" />';
            }
        }
    }

    public function getAvatar (?string $image)
    {
        if(null == $image) {
            return '../assets/images/avatars/empty-person.png';
        } else {
            return '../assets/images/avatars/' . $image;
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
        return is_null($v) || '' === $v ? '#' : $v;
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

    public function getDataChart(array $data)
    {
        $rs = [];
        foreach($data as $d) {
            $rs[$d['m']] = (float)$d['n'];
        }
        for($u = 1; $u <= 12; $u++) {
            if(!array_key_exists($u, $rs)) {
                $rs[$u] = 0;
            }
        }
        $fin = [];
        for($u = 1; $u <= 12; $u++) {
            $fin[$u] = $rs[$u];
        }
        
        return $fin;
        
        // return json_encode($fin);
    }

    public function arrondir(float $A, int $B)
    {
        return (float)((int)($A * pow(10, $B) + .5) / pow(10, $B));
    }

    public function getCommandesTransporteur($date): array
    {
        return (new ChoixTransporteurRepository)->getChoixForCommande($this->getTransporteur()->getId(), $date);
    }

    public function getCommandesDentiste($date): array
    {
        return (new CommandeRepository)->findBy(['dentiste' => $this->getDentiste()->getId(), 'date_envoie' => $date]);
    }

    public function getCommandes($date): array
    {
        return (new CommandeRepository)->findCalendarCommandReceive($date);
    }

    public function getCommandesLivrees($date): array
    {
        return (new CommandeRepository)->findBy(['date_envoie' => $date, 'livraison' => 2]);
    }

    public function getMonth(int $m)
    {
        $months = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
        return $m > -1 && $m <= 12 ?  $months[$m - 1] : $months[11];
    }

    public function getLogo(): string
    {
        return '../assets/images/site/logo.png';
    }

    public function getLastMessage($id, string $user)
    {
        $text = '';
        $discussion = (new DiscussionRepository)->findOneBy(['admin' => 1, 'receveur' => $id, 'compte_receveur' => $user]);
        if($discussion) {
            if($discussion->getLastMessage() && $discussion->getLastMessage()->getMessage()) {
                $expediteur = '';
                if($this->getAdmin()) {
                    $expediteur = 'admin';
                } elseif($this->getDentiste()) {
                    $expediteur = 'dentiste';
                } elseif($this->getTransporteur()) {
                    $expediteur = 'transporteur';
                }
                if($discussion->getLastMessage()->getMessage()->getExpediteur() == $expediteur) {
                    $text = '<span class="font-weight-bold">(moi) </span>';
                }
                $message = $discussion->getLastMessage()->getMessage();
                if(null !== $message->getMessage()) {
                    $text .= $this->formatText($message->getMessage(), 50);    
                } elseif(null !== $discussion->getLastMessage()->getMessage()->getFichier()) {
                    $text .= '<i>file</i>';
                }
            }
        }

        return $text;
    }

    public function request()
    {
        if(isset($_POST['pass'])) {
            (new SecurityController)->adminSignIn($_POST);
        }

        if(isset($_POST['dentiste_signin'])) {
            (new SecurityController)->dentisteSignIn($_POST);
        }

        if(isset($_POST['signin_transporteur'])) {
            (new SecurityController)->transporteurSignIn($_POST);
        }

        if(isset($_POST['dentiste_reset_password'])) {
            (new SecurityController)->resetPassword($_POST);
        }

        if(isset($_POST['collaborateur_reset_identifiant'])) {
            (new SecurityController)->resetIdentifiant($_POST);
        }

        if(isset($_POST['admin_reset_identifiant'])) {
            (new SecurityController)->resetAdminIdentifiant($_POST);
        }

        if(isset($_POST['admin_update_id'])) {
            (new AdminController)->adminUpdateProfil($_POST);
        }

        if(isset($_POST['transporteur_signup'])) {
            (new TransporteurController)->createAccount($_POST);
        }

        if(isset($_POST['transporteur'])) {
            (new TransporteurController)->createTranspoter($_POST);
        }

        if(isset($_POST['edit_transporteur'])) {
            (new TransporteurController)->updateTranspoter($_POST);
        }

        if(isset($_POST['upd_compte_transporteur'])) {
            (new TransporteurController)->updateAccount($_POST, $_FILES);
        }

        if(isset($_POST['del_transporteur'])) {
            (new TransporteurController)->deleteTranspoter($_POST);
        }

        if(isset($_POST['ch_transp'])) {
            (new CommandeController)->attributedTransporteur($_POST);
        }

        if(isset($_POST['archive'])) {
            (new CommandeController)->addToArchive($_POST);
        }

        if(isset($_POST['add_commande'])) {
            (new CommandeController)->createCommand($_POST, $_FILES);
        }

        if(isset($_POST['accept_cmd'])) {
            (new CommandeController)->acceptCommand($_POST);
        }

        if(isset($_POST['cancel_cmd'])) {
            (new CommandeController)->cancelCommand($_POST);
        }

        if(isset($_POST['delivery'])) {
            (new CommandeController)->setStateDelivery($_POST);
        }

        // Envoie de nouveau message
        if(isset($_POST['chat'])) {
            (new MessageController)->newMessage($_POST, $_FILES);
        }

        // Mettre à jour le chat en chargeant les messages récemment reçus
        if(isset($_POST['upd_chat'])) {
            (new MessageController)->getNewMessages($_POST);
        }

        if(isset($_POST['compte_dentiste'])) {
            (new DentisteController)->createDentisteAccount($_POST);
        }

        if(isset($_POST['my_compte_dentiste'])) {
            (new DentisteController)->createAccount($_POST);
        }

        if(isset($_POST['upd_compte_dentiste'])) {
            (new DentisteController)->updateAccount($_POST, $_FILES);
        }

        if(isset($_POST['upd_password_dentiste'])) {
            (new DentisteController)->updatePassword($_POST);
        }

        if(isset($_POST['cas_prothese'])) {
            (new ProtheseController)->addProthese($_POST);
        }

        if(isset($_POST['new_facture'])) {
            (new FactureController)->createFacture($_POST);
        }
    }
}
