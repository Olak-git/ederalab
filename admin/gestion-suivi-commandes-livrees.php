<?php
// name: gestion_commandes_livrees
// route: gestion-commandes-livrees

use src\Repository\CommandeRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$commandeRepository = new CommandeRepository;

$commandes = $commandeRepository->findCommandeLivree();

$alink = 2;
$code = 2;
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
                <div class="btn-block d-flex justify-content-between align-items-center bg-white p-2 rounded-fit border border-ederalab text-dark text-decoration-none" style="min-height:50px;">
                    <a href="#" 
                        type="button" 
                        data-toggle="modal" 
                        data-target="#commandeModal" 
                        data-name="suivi" 
                        data-name-value="cmd_livree" 
                        class="btn-block text-decoration-none text-dark show-modal-commande" style="max-width:80%;">
                        <?= $commande->getDentiste()->getUsername(); ?>, <?= $commande->getUsernamePatient(); ?>
                    </a>
                    <form method="post" class="my-0">
                        <button disable class="btn-link small rounded d-inline-block cursor-pointer text-center py-1 px-2" style="width:70px;">Archiver</button>
                        <input type="hidden" name="archive[commande]" value="<?= $commande->getId(); ?>" />
                        <input type="hidden" name="csrf" value="<?= password_hash('add-archive', 1); ?>" />
                    </form>
                    <!-- <a href="" class="small rounded btn-link py-1 px-2">Archiver</a> -->
                </div>
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