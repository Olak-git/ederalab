<?php
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$factures = $router->getDentiste()->getFactures();

// $header_link = 1;

ob_start();
?>
    <style>

    </style>
<?php
$style = ob_get_clean();

ob_start();
    
?>
    <div class="d-flex flex-wrap justify-content-between align-items-center p-3">

        <?php foreach($factures as $facture): ?>
            <?php $commande = $facture->getCommande(); ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card p-2">
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
            </div>
        <?php endforeach; ?>

    </div>

<?php 
$main = ob_get_clean();

ob_start();
?>
    <script>

    </script>
<?php
    $script = ob_get_clean();

require 'base.php';