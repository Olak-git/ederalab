<?php

use src\Repository\CommandeRepository;
use src\Repository\DentisteRepository;
use src\Repository\TransporteurRepository;
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->request();

if(isset($_POST['async'])) {

    if(isset($_POST['show-command'])) {
        $commande = (new CommandeRepository)->findOneBy(['slug' => $_POST['show-command']]);
        include 'layouts/_modal-command-data.php';
    }

    if(isset($_POST['calendrier'])) {

        $calendrier = $_POST['calendrier'];

        if($calendrier == 'cmd_recue') {
            $commandes = (new CommandeRepository)->getCommandesForTransporter($router->getTransporteur()->getId(), $_POST['date']);
        } 
        // elseif($calendrier == 'cmd_livree') {
        //     $clink = 2;
        //     $commandes = (new CommandeRepository)->findBy(['date_envoie' => $_POST['date'], 'livraison' => 2]);
        // } elseif($calendrier == 'reception_fournisseur') {
        //     $clink = 3;
        //     $commandes = (new CommandeRepository)->getCommandeByTransReceptionDate($_POST['date']);
        // }

        if(isset($commandes)) {
            include 'layouts/_modal-command-data.php';
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
                if($message->getExpediteur() === 'transporteur') {
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