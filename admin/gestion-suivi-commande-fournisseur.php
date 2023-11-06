<?php
// name: gestion_commande_choix_fournisseur
// route: gestion-commande-choix-fournisseur

use src\Router\Router;
use src\Repository\CommandeRepository;
use src\Repository\TransporteurRepository;

include '../autoload.php';

$router = new Router;

$router->request();

$router->adminIsConnected();

if(!empty($_GET['commande'])) {
    $commande = (new CommandeRepository)->findOneBy(['archive' => 0, 'slug' => $_GET['commande']]);
}

$transporteurs = (new TransporteurRepository)->findBy(['del' => 0]);

$alink = 2;
$code = 3;
?>
    <style>
        .col-center {
            width: 95%;
            position:relative;
            display:bolck;
            max-height: calc(100vh - 300px);
            overflow:hidden;
            overflow-y:auto;
        }

        .custom-control-label::before {
            width: 1.5rem !important;
            height: 1.5rem !important;
            border: 1px solid var(--pal) !important;
            background-color: #fff !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            box-shadow: 2px 2px 1px var(--pal) !important;
        }
        .custom-control-label::after {
            width: 1.5rem !important;
            height: 1.5rem !important;
        }
        .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--pal) !important;
            box-shadow: none !important;
        }
        .custom-checkbox .custom-control-input:checked~.custom-control-label::after {
            background-image: url('../assets/images/site/check2.svg') !important;
            background-size: 24px;
        }
    </style>
<?php
    ob_start();

    include 'layouts/_profil-transporteur-modal.php';
?>

    <div class="w-100 d-flex justify-content-center align-items-center mt-4">
        <div class="text-right px-2" style="position:sticky;top:20px;width:95%;">
            <a href="#" class="back-button btn-link small d-inline-block text-center p-2" style="width:80px;border-radius:.5rem;">Retour</a>
        </div>
    </div>

    <?php if(!empty($commande) && $commande->getChoixTransporteur()): ?>
        <div class="w-100 d-flex justify-content-center align-items-center mt-4">
            <div class="px-2" style="width:95%;">
                <p>Commande envoyée à <span class="text-success font-weight-bold"><?= $commande->getTransporteur()->getUsername(); ?></span> le <?= (new \DateTime($commande->getChoixTransporteur()->getDateReception()))->format('d/m/Y'); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="w-100 d-flex justify-content-center align-items-center my-4">
        <div class="col-center px-2" style="">

            <?php if(isset($commande) && null !== $commande): ?>
                <?php foreach($transporteurs as $transp): ?>
                    <div class="d-flex justify-content-between align-items-center bg-white p-1 mb-3" style="border:1px solid silver;border-radius:1rem;min-height:60px;">
                        <a href="#" 
                            type="button" 
                            data-toggle="modal" 
                            data-target="#profilTranspModal" 
                            data-username="<?= $transp->getUsername(); ?>" 
                            data-address="<?= $transp->getAdresse(); ?>" 
                            data-phone="<?= $transp->getPhone(); ?>" 
                            class="col-11 text-decoration-none text-dark">
                            <div class="d-flex flex-wrap w-100">
                                <div class="col-12 col-sm-6 col-md-8"><?= $transp->getUsername() . ', ' . $transp->getAdresse(); ?></div>
                                <div class="col-12 col-sm-6 col-md-4"><?= $transp->getPhone(); ?></div>
                            </div>
                        </a>
                        <div class="col text-right px-0">
                            <div class="custom-control custom-checkbox mb-2 mr-sm-2">
                                <input type="radio" name="transp" class="custom-control-input check" id="customControlInline<?= $transp->getId(); ?>" value="<?= $transp->getId(); ?>">
                                <label class="custom-control-label shadow-none" for="customControlInline<?= $transp->getId(); ?>"></label>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                Oups !!!
            <?php endif; ?>

        </div>
    </div>

    <div class="text-center">
        <?php if(!$router->getShowNotification()): ?>
            <?php if(isset($commande) && null !== $commande): ?>
                <form method="post" class="my-0">
                    <button disable class="btn-link small d-inline-block cursor-pointer text-center p-2" style="width:80px;border-radius:.5rem;">Envoyer</button>
                    <input type="hidden" name="ch_transp[commande]" value="<?= $commande->getId(); ?>" />
                    <input type="hidden" name="ch_transp[transporteur]" id="ch_transporteur" />
                    <input type="hidden" name="csrf" value="<?= password_hash('choice-transporter', 1); ?>" />
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <!-- <script src="../assets/js/load-data-modal.js"></script> -->
    <script>
        S('.check').forEach(c => {
            c.onclick = function() {
                if(c.checked) {
                    if(Z('#ch_transporteur')) {
                        Z('#ch_transporteur').value = c.value
                    }
                }
            }
        })
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';