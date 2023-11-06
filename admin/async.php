<?php

use src\Repository\CommandeRepository;
use src\Repository\DentisteRepository;
use src\Repository\TransporteurRepository;
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->request();

if(isset($_POST['async'])) {

    if(isset($_POST['accept_cmd']) || isset($_POST['cancel_cmd'])) {
        if(!empty($router->getError())) {
            echo json_encode(['success' => false, 'errors' => $router->getError()]);    
        } else {
            echo json_encode(['success' => true]);
        }
    }

    if(isset($_POST['calendrier'])) {

        $calendrier = $_POST['calendrier'];

        if($calendrier == 'cmd_recue') {
            $clink = 1;
            $commandes = (new CommandeRepository)->findCalendarCommandReceive($_POST['date']);
        } elseif($calendrier == 'cmd_livree') {
            $clink = 2;
            $commandes = (new CommandeRepository)->findBy(['date_envoie' => $_POST['date'], 'livraison' => 2]);
        } elseif($calendrier == 'reception_fournisseur') {
            $clink = 3;
            $commandes = (new CommandeRepository)->getCommandeByTransReceptionDate($_POST['date']);
        }

        if(isset($commandes)) {
            include 'layouts/calendrier-planification/_load-modal-commande.php';
        }
    }

    elseif(isset($_POST['suivi'])) {
        $suivi = $_POST['suivi'];
        
        if($suivi == 'cmd_recue') {
            $code = 1;
            $commandes = (new CommandeRepository)->findCommandeRecue();
        } elseif($suivi == 'cmd_livree') {
            $code = 2;
            $commandes = (new CommandeRepository)->findCommandeLivree();
        } elseif($suivi == 'cmd_attente') {
            $code = 3;
            $commandes = (new CommandeRepository)->findCommandeEnAttente();
        } elseif($suivi == 'cmd_annulee') {
            $code = 4;
            $commandes = (new CommandeRepository)->findCommandeAnnulee();
        }

        if(isset($commandes)) {
            include 'layouts/gestion-suivi-commandes/_load-modal-commande-recue.php';
        }
    }
    
    // if(isset($_POST['commande'])) {
    //     $cmd = $_POST['commande'];
    //     if($cmd == 'recue' && isset($_POST['date_envoie'])) {
    //         $commandes = (new CommandeRepository)->findBy(['date_envoie' => $_POST['date_envoie']]);
    //         include 'layouts/calendrier-planification/_load-modal-commande-recue.php';
    //     } elseif($cmd == 'livree') {
    //         $commandes = (new CommandeRepository)->findBy(['date_envoie' => $_POST['date_envoie'], 'livraison' => 2]);
    //         include 'layouts/calendrier-planification/_load-modal-commande-livree.php';
    //     }
    // }

    elseif(isset($_POST['transporteur']) || isset($_POST['edit_transporteur'])) {
        if(!empty($router->getError())) {
            echo json_encode(['success' => false, 'errors' => $router->getError()]);    
        } else {
            echo json_encode(['success' => true]);
        }
    }

    // Envoie de nouveau message
    elseif(isset($_POST['chat'])) {
        if(!empty($router->getError())) {
            echo json_encode(['xhkerrors' => $router->getError()]);
        } else {
            if($router->get_messages()) {
                $message = $router->get_messages()[0];
                include('../layouts/message/_message-right.php');
            } else {
                echo json_encode(true);
            }
        }
    }

    // Mettre à jour le chat en chargeant les messages récemment reçus
    elseif(isset($_POST['upd_chat'])) {
        if(!empty($router->get_messages())) {
            $messages = $router->get_messages();
            foreach($messages as $message):
                if($message->getExpediteur() === 'admin') {
                    include('../layouts/message/_message-right.php');
                } else {
                    include('../layouts/message/_message-left.php');
                }
            endforeach;
        }
    }

    elseif(isset($_POST['upd_conversation'])) {
        $updc = $_POST['upd_conversation'];
        if(isset($updc['user'])) {
            $dentisteRepository = new DentisteRepository;
            $transporteurRepository = new TransporteurRepository;
            
            $receveurs = [];

            $user = strtolower($updc['user']);
            if($user == 'dentiste') {
                $receveurs = $dentisteRepository->findAll();
            } elseif($user == 'transporteur') {
                $receveurs = $transporteurRepository->findAll();
            }

            if(isset($updc['user_active'])) {
                $user_active = $updc['user_active'];
            }

            foreach($receveurs as $receveur) {
                include 'layouts/message/_user.php';
            }
        }
    }
}