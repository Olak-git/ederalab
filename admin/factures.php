<?php
// name: factures
// route: factures

use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$db = $router->getDb();

$commandes = $db->query(
    "SELECT c.*, d.nom nom_dentiste, d.prenom prenom_dentiste
    FROM commande c 
    INNER JOIN dentiste d 
    ON c.dentiste = d.id 
    WHERE c.valide=1 AND c.livraison=2"
)->fetchAll();

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
        .border-right-0 {
            border-right:none !important;
        }
        .border-top-0 {
            border-top:0;
        }
    </style>
<?php
    $style = ob_get_clean();

    ob_start();

    $code = 1;
    include 'layouts/gestion-suivi-commandes/_commande-recue-modal.php';
?>

    <div class="main w-100 d-flex justify-content-center mt-4">
        
        <div class="col-12" style="overflow-x:auto;">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Dentiste</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th class="border-right-0"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($commandes as $cmd): 
                        $facture = $db->findOneBy("facture", ['commande' => $cmd["id"]])    
                    ?>
                        <?php /*<a href="#" 
                            type="button" 
                            data-toggle="modal" 
                            data-target="#commandeModal" 
                            data-name="suivi" 
                            data-name-value="cmd_livree" 
                            class="d-inline-block text-decoration-none text-dark show-modal-commande border border-ederalab" style="max-width:80%;">
                            <?= $router->getUsername($cmd["nom_dentiste"], $cmd["prenom_dentiste"]); ?>, <?= $router->getUsername($cmd["nom_patient"], $cmd["prenom_patient"]); ?>
                        </a>*/ ?>
                        <tr>
                            <td>
                                <a href="#"
                                    type="button" 
                                    data-toggle="modal" 
                                    data-target="#commandeModal" 
                                    data-name="suivi" 
                                    data-name-value="cmd_livree" 
                                    class="btn-block show-modal-commande text-decoration-none text-dark">
                                    <span class="d-flex align-items-center">
                                        <span class="d-flex justify-content-center align-items-center p-1 mr-2" style="background-color:#dee2e6;width:40px;min-width:40px;height:40px;border-radius:50%;">
                                            <img src="../assets/images/avatars/empty-person.png" class="mw-100 mh-100">
                                        </span>
                                        <?= $router->getUsername($cmd["nom_dentiste"], $cmd["prenom_dentiste"]); ?>
                                    </span>
                                </a>
                            </td>
                            <td>
                                <a href="#"
                                    type="button" 
                                    data-toggle="modal" 
                                    data-target="#commandeModal" 
                                    data-name="suivi" 
                                    data-name-value="cmd_livree" 
                                    class="btn-block show-modal-commande text-decoration-none text-dark">
                                    <?= $router->getUsername($cmd["nom_patient"], $cmd["prenom_patient"]); ?></a>
                                </td>
                            <td>
                                <a href="#"
                                    type="button" 
                                    data-toggle="modal" 
                                    data-target="#commandeModal" 
                                    data-name="suivi" 
                                    data-name-value="cmd_livree" 
                                    class="btn-block show-modal-commande text-decoration-none text-dark">
                                    <?= $cmd["date_envoie"]; ?>
                                </a>
                            </td>
                            <td>
                                <?php if(!$facture): ?>
                                    <a href="editer-facture.php?key=<?= $cmd["slug"]; ?>" class="badge text-decoration-none">Editer la facture</a>
                                <?php else: ?>
                                    <a href="facture.php?key=<?= $facture["slug"]; ?>" class="badge text-decoration-none">Voir la facture</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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