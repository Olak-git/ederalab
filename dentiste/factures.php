<?php
use src\Router\Router;

require_once '../autoload.php';

$router = new Router;

$router->dentisteIsConnected();

$db = $router->getDb();
$user = $router->getDentiste();

$factures = $db->query(
    "SELECT f.*, c.nom_patient, c.prenom_patient 
    FROM facture f 
    INNER JOIN commande c 
        ON f.commande = c.id 
    INNER JOIN dentiste d 
        ON c.dentiste = d.id 
    WHERE d.id = :did", ["did" => $user["id"]]
)->fetchAll();

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
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card p-2">
                    <h5 class="text-center mb-4">Facture n°<?= $facture->getCode(); ?></h5>
                    <div class="badge d-block text-left mb-2">Date : <?= (new \DateTime($facture["dat"]))->format('d/m/Y'); ?></div>
                    <div class="badge d-block text-left mb-2">Client : <?= $router->getUsername($facture["nom_patient"], $facture["prenom_patient"]); ?></div>
                    <div class="badge d-block text-left mb-4">Ville/Adresse : <?= $user["adresse"]; ?></div>

                    <h6 class="mb-3">Commande et prix:</h6>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Eléments</td>
                                <td>Prix (<?= $facture["devis"]; ?>)</td>
                            </tr>
                            <tr>
                                <td>
                                    <?php 
                                        $protheses = $db->query(
                                            "SELECT p.numero numero_prothese 
                                            FROM choix_prothese cP 
                                            INNER JOIN prothese p 
                                                ON cP.prothese = p.id 
                                            INNER JOIN commande c 
                                                ON cP.commande = c.id 
                                            WHERE c.id = :cid", ["cid" => $facture["commande"]]
                                        )->fetchAll();
                                    ?>
                                    Cas n° <?php foreach($protheses as $proth) {
                                        echo $proth["numero_prothese"] . ', ';
                                    } ?>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total HT</td>
                                <td><?= $facture["total_ht"]; ?></td>
                            </tr>
                            <tr>
                                <td>TVA</td>
                                <td><?= $router->arrondir(($facture["total_ht"] * 100) / $facture["tva"], 2); ?></td>
                            </tr>
                            <tr>
                                <td>Total TTC</td>
                                <td><?= $router->arrondir($facture["total_ht"] + (($facture["total_ht"] * 100) / $facture["tva"]), 2); ?></td>
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