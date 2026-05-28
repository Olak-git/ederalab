<?php

use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->request();

$db = $router->getDb();

if(isset($_POST['async'])) {

    if(isset($_POST['show-command'])) {
        $commande = $db->findOneBy("commande", ['slug' => $_POST['show-command']]);
        include 'layouts/_modal-command-data.php';
    }

    if(isset($_POST['calendrier'])) {

        $calendrier = $_POST['calendrier'];

        if($calendrier == 'cmd_recue') {
            $commandes = $db->query(
                "SELECT c.* 
                FROM commande c 
                INNER JOIN choix_transporteur ch 
                    ON ch.commande = c.id 
                INNER JOIN transporteur t 
                    ON ch.transporteur = t.id 
                WHERE t.id = :tid 
                AND EXTRACT(YEAR FROM date_reception) = :cdate", ["tid" => $router->getTransporteur()["id"], "cdate" => $_POST['date']]
            )->fetchAll();
        } 
        // elseif($calendrier == 'cmd_livree') {
        //     $clink = 2;
        //     $commandes = $db->findBy("commande", ['date_envoie' => $_POST['date'], 'livraison' => 2]);
        // } elseif($calendrier == 'reception_fournisseur') {
        //     $clink = 3;
        //     $commandes = $db->query(
        //         "SELECT DISTINCT(c.id), c.* 
        //         FROM commande c 
        //         INNER JOIN choix_transporteur ct 
        //             ON ct.commande = c.id 
        //         INNER JOIN transporteur t 
        //             ON ct.transporteur = t.id 
        //         WHERE Date(ct.date_reception)=:dat", ["dat" => $_POST['date']]
        //     )->fetchAll();
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
                $message = $router->get_messages();
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
                if($message["expediteur"] === 'transporteur') {
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
            
            $receveurs = [];

            $user = strtolower($updc['user']);
            if($user == 'dentiste') {
                $receveurs = $db->findAll("dentiste");
            } elseif($user == 'transporteur') {
                $receveurs = $db->findAll("transporteur");
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