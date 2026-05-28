<?php
if(!empty($commandes)) {
    echo '<nav>'
            .'<div class="nav nav-tabs" id="nav-tab" role="tablist">';
    foreach ($commandes as $key => $commande) {
        echo '<a class="nav-item nav-link' . ($key == 0 ? ' active' : '') . '" id="nav-' . $commande["slug"] . '-tab" data-toggle="tab" href="#nav-' . $commande["slug"] . '" role="tab" aria-controls="nav-' . $commande["slug"] . '" aria-selected="true">' . $router->getUsername($commande["nom_dentiste"], $commande["prenom_dentiste"]) . '</a>';
    }
    echo    '</div>'
        .'</nav>';
    
    echo '<div class="tab-content" id="nav-tabContent">';
    foreach ($commandes as $key => $commande) {
    ?>
        <div class="tab-pane fade<?= ($key == 0 ? ' show active' : '') ?>" id="nav-<?= $commande["slug"]; ?>" role="tabpanel" aria-labelledby="nav-<?= $commande["slug"]; ?>-tab">
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Nom & Prénom du dentiste :</div>
                <div class="col-6 font-weight-bold" id="username-dentiste"><?= $router->getUsername($commande["nom_dentiste"], $commande["prenom_dentiste"]); ?></div>
            </div>
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Cabinet :</div>
                <div class="col-6 font-weight-bold" id="username-cabinet"><?= $commande["cabinet_dentiste"]; ?></div>
            </div>
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Ville / Adresse :</div>
                <div class="col-6 font-weight-bold" id="address"><?= $commande["adresse_dentiste"]; ?></div>
            </div>
            <div class="mb-4">
                <div class="mb-2">Commande :</div>
                <div>
                    <table class="table-details">
                        <thead>
                            <tr>
                                <td class="text-center">Nom du patient</td>
                                <td class="text-center">Cas n°</td>
                                <td class="text-center">Détails de la commande</td>
                                <td class="text-center">Modif. demandée</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $choix_protheses = $router->getDb()->query(
                                    "SELECT cP.*, p.numero numero_prothese
                                    FROM choix_prothese cP
                                    INNER JOIN commande c
                                    ON cP.commande = c.id
                                    INNER JOIN prothese p
                                    ON cP.prothese = p.id
                                    WHERE cP.commande=:cmd", ["cmd" => $commande["id"]]
                                )->fetchAll();
                            foreach($choix_protheses as $prot): 
                            ?>
                                <tr>
                                    <td id="username-patient"><?= $router->getUsername($commande["nom_patient"], $commande["prenom_patient"]); ?></td>
                                    <td id="number-case"><?= $prot["numero_prothese"]; ?></td>
                                    <td id="details-commande"><?= nl2br($router->getValue($prot["details_commande"])); ?></td>
                                    <td id="modif"><?= nl2br($router->getValue($prot["modif_demand"])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <form method="post" action="" name="" class="" onsubmit="window.event.preventDefault();console.log(this)">
                <div class="text-center"><button type="butto" class="btn px-4 bg-ederalab text-white border-0" style="border-radius:.5rem;">Accepter la commande</button></div>
                <input type="hidden" name="csrf" value="<?= password_hash('edit-' . (isset($pre) ? strtolower($pre) : '') . 'categorie', 1); ?>">
            </form>
        </div>
    <?php
        // echo '<div class="tab-pane fade show active" id="nav-' . $commande["slug"] . '" role="tabpanel" aria-labelledby="nav-' . $commande["slug"] . '-tab">...</div>';
    }
    echo '</div>';
}