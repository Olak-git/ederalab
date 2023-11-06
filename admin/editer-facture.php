<?php
// name: editer_facture
// route: editer-facture-([a-z]*)

use src\Repository\CommandeRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

if(isset($_GET['key'])) {
    $commande = (new CommandeRepository)->findOneBy(['valide' => 1, 'livraison' => 2, 'slug' => $_GET['key']]);
}

$hlink = 2;

    ob_start();
?>
    <style>
        main {
            padding-top: var(--nav-top-height);
            height: 100vh;
            padding-bottom: 0;
        }
        a.params-link {
            background-color: #fff;
            border-radius: .25rem;
            padding:.55rem 1rem;
            display: block;
            width: 250px;
            color:#000;
            margin-bottom: .5rem;
            border: 1px solid #fff;
        }
        a.params-link.active {
            background-color: var(--pal);
            color: #fff;
            border-color: var(--pal);
        }
        a.params-link:hover {
            border-color: var(--pal);
        }
    </style>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="main w-100 d-flex justify-content-center mt-5">
        
        <div class="col-12 col-md-6" class="" style="overflow-x:auto;">

            <?php if(!empty($commande)): ?>
        
                <form method="post" class="bg-white border p-4" style="border:1px solid #f4f4f4;border-radius:.5rem;">
                    <div class="badge d-block text-left mb-2">Date : <?= date('d/m/Y'); ?></div>
                    <div class="badge d-block text-left mb-2">Client : <?= $commande->getUsernamePatient(); ?></div>
                    <div class="badge d-block text-left mb-4">Ville/Adresse : <?= $commande->getDentiste()->getAdresse(); ?></div>

                    <h6 class="mb-3">Commande et prix:</h6>

                    <div class="form-group mb-" id="total_ht">
                        <div class="d-flex align-items-center">
                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="new_facture_total_ht" class="">Total HT</label><span class="">:</span></div>
                            <div class="col"><input type="" name="new_facture[total_ht]" class="form-control shadow-none" id="new_facture_total_ht" placeholder="1.00" value="<?= $router->getValPost(['new_facture', 'total_ht']); ?>"></div>
                        </div>
                        <?= $router->errorHTML2('total_ht'); ?>
                    </div>
                    <div class="form-group mb-" id="tva">
                        <div class="d-flex align-items-center">
                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="new_facture_tva" class="">TVA (%)</label><span class="">:</span></div>
                            <div class="col"><input type="" name="new_facture[tva]" class="form-control shadow-none" id="new_facture_tva" placeholder="18" value="<?= $router->getValPost(['new_facture', 'tva']); ?>"></div>
                        </div>
                        <?= $router->errorHTML2('tva'); ?>
                    </div>
                    <div class="form-group mb-" id="devis">
                        <div class="d-flex align-items-center">
                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="new_facture_devis" class="">Devis</label><span class="">:</span></div>
                            <div class="col">
                                <select name="new_facture[devis]" id="new_facture_devis" class="form-control shadow-none" id="exampleFormControlSelect1">
                                    <option value="eur">EUR</option>
                                    <option value="usd">USD</option>
                                </select>
                            </div>
                        </div>
                        <?= $router->errorHTML2('devis'); ?>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button class="btn p-2 btn-ederalab shadow-none" style="min-width:190px;border-radius:10px;">Créer la facture</button>
                    </div>

                    <input type="hidden" name="new_facture[cmd]" value="<?= $commande->getSlug(); ?>">
                    <input type="hidden" name="csrf" value="<?= password_hash('admin-create-facture', 1); ?>">
                </form>

            <?php endif; ?>

        </div>
        
    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script src="../assets/js/load-data-modal.js"></script>

<?php
    $script = ob_get_clean();

require 'base.php';