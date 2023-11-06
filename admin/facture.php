<?php
// name: facture
// route: facture-([a-z]*)

use src\Repository\FactureRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

if(isset($_GET['key'])) {
    $facture = (new FactureRepository)->findOneBy(['slug' => $_GET['key']]);
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

    <a href="<?= $router->getRoutes()->path('factures'); ?>" style="position:fixed;left:150px;top:92px;color:#000 !important;"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg></a>

    <div class="main w-100 d-flex justify-content-center mt-5">
        
        <div class="col-12 col-md-6" class="" style="overflow-x:auto;">

            <?php if(!empty($facture)): ?>

                <?php $commande = $facture->getCommande(); ?>
        
                <div class="bg-white border p-5" style="border:1px solid #f4f4f4;border-radius:.5rem;">

                    <h5 class="text-center mb-4">Facture n°<?= $facture->getCode(); ?></h5>
                    <div class="badge d-block text-left mb-2">Date : <?= (new \DateTime($facture->getDat()))->format('d/m/Y'); ?></div>
                    <div class="badge d-block text-left mb-2">Client : <?= $commande->getUsernamePatient(); ?></div>
                    <div class="badge d-block text-left mb-4">Ville/Adresse : <?= $commande->getDentiste()->getAdresse(); ?></div>

                    <h6 class="mb-3">Commande et prix:</h6>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Eléments</td>
                                <td>Prix (<?= $facture->getDevis(); ?>)</td>
                            </tr>
                            <tr>
                                <td>
                                    <?php $protheses = $commande->getChoixProtheses(); ?>
                                    Cas n° <?php foreach($protheses as $proth) {
                                        echo $proth->getProthese()->getNumero() . ', ';
                                    } ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total HT</td>
                                <td><?= $facture->getTotalHt(); ?></td>
                            </tr>
                            <tr>
                                <td>TVA</td>
                                <td><?= $router->arrondir(($facture->getTotalHt() * 100) / $facture->getTva(), 2); ?></td>
                            </tr>
                            <tr>
                                <td>Total TTC</td>
                                <td><?= $router->arrondir($facture->getTotalHt() + (($facture->getTotalHt() * 100) / $facture->getTva()), 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

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