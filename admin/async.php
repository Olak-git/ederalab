<?php

use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->request();

$db = $router->getDb();

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
            $request = $db->query(
                "SELECT c.*, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id
                WHERE c.date_envoie = :dat 
                AND c.archive = 0 
                AND c.valide != -1 
                AND c.livraison != 2", ["dat" => $_POST['date']]
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        } elseif($calendrier == 'cmd_livree') {
            $clink = 2;
            $request = $db->query(
                "SELECT c.*, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id 
                WHERE date_envoie = :dat 
                AND livraison = 2", ["dat" => $_POST['date']]
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        } elseif($calendrier == 'reception_fournisseur') {
            $clink = 3;
            $request = $db->query(
                "SELECT DISTINCT(c.id), c.*, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste, t.mom nom_transporteur, t.prenom prenom_transporteur
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id
                INNER JOIN choix_transporteur ct 
                ON ct.commande = c.id 
                INNER JOIN transporteur t 
                ON ct.transporteur = t.id 
                WHERE Date(ct.date_reception)=:dat", ['dat' => $_POST['date']]
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        }

        if(isset($commandes)) {
            include 'layouts/calendrier-planification/_load-modal-commande.php';
        }
    }

    elseif(isset($_POST['suivi'])) {
        $suivi = $_POST['suivi'];
        
        if($suivi == 'cmd_recue') {
            $code = 1;
            $request = $db->query(
                "SELECT c.*, d.slug slug_dentiste, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id
                WHERE c.archive = 0 
                AND c.archive = 0"
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        } elseif($suivi == 'cmd_livree') {
            $code = 2;
            $request = $db->query(
                "SELECT c.*, d.slug slug_dentiste, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id 
                WHERE c.archive = 0
                AND c.valide = 1  
                AND c.livraison = 2"
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        } elseif($suivi == 'cmd_attente') {
            $code = 3;
            $request = $db->query(
                "SELECT c.*, d.slug slug_dentiste, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id 
                WHERE c.archive = 0 
                AND c.valide = 1
                AND c.livraison = 0"
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        } elseif($suivi == 'cmd_annulee') {
            $code = 4;
            $request = $db->query(
                "SELECT c.*, d.slug slug_dentiste, d.nom nom_dentiste, d.prenom prenom_dentiste, d.cabinet cabinet_dentiste, d.adresse adresse_dentiste
                FROM commande c
                INNER JOIN dentiste d
                ON c.dentiste = d.id 
                WHERE c.archive = 0 
                AND c.valide = -1"
            );
            $commandes = $request->fetchAll();
            $request->closeCursor();
        }

        if(isset($commandes)) {
            include 'layouts/gestion-suivi-commandes/_load-modal-commande-recue.php';
        }
    }
    
    // if(isset($_POST['commande'])) {
    //     $cmd = $_POST['commande'];
    //     if($cmd == 'recue' && isset($_POST['date_envoie'])) {
    //         $commandes = $db->findBy("commande", ['date_envoie' => $_POST['date_envoie']]);
    //         include 'layouts/calendrier-planification/_load-modal-commande-recue.php';
    //     } elseif($cmd == 'livree') {
    //         $commandes = $db->findBy("commande", ['date_envoie' => $_POST['date_envoie'], 'livraison' => 2]);
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
                if($message["expediteur"] === 'admin') {
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