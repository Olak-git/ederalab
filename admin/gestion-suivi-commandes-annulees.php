<?php
// name: gestion_commandes_annulees
// route: gestion-commandes-annulees

use src\Router\Router;
use src\Repository\CommandeRepository;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$commandeRepository = new CommandeRepository;

$commandes = $commandeRepository->findCommandeAnnulee();

$alink = 2;
$code = 4;
?>
    <link rel="stylesheet" href="./assets/css/style-gestion-suivi-commande.css">
<?php
    ob_start();

    include 'layouts/gestion-suivi-commandes/_commande-recue-modal.php';
?>

    <div class="w-100 d-flex justify-content-between align-items-start mt-4">

        <div class="col-left" style="">
            <?php include 'layouts/gestion-suivi-commandes/_menu.php'; ?>
        </div>
        <div class="col-right px-2" style="">

            <?php foreach($commandes as $commande): ?>
                <a href="#" 
                    type="button" 
                    data-toggle="modal" 
                    data-target="#commandeModal" 
                    data-name="suivi" 
                    data-name-value="cmd_annulee" 
                    class="btn-block d-flex justify-content-between align-items-center bg-white p-2 rounded-fit text-dark text-decoration-none show-modal-commande border border-ederalab" style="min-height:50px;">
                    <div class="" style="max-width:80%;">
                        <?= $commande->getDentiste()->getUsername(); ?>, <?= $commande->getUsernamePatient(); ?>
                    </div>
                    <span class="font-weight-bold"><?= (new \DateTime($commande->getDateReceptionPrevue()))->format('d/m/Y'); ?></span>
                </a>                
            <?php endforeach; ?>
            
        </div>
    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script src="../assets/js/load-data-modal.js"></script>
    <script>
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';