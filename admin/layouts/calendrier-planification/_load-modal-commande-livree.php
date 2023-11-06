<?php
if(!empty($commandes)) {
    echo '<nav>'
            .'<div class="nav nav-tabs" id="nav-tab" role="tablist">';
    foreach ($commandes as $key => $commande) {
        echo '<a class="nav-item nav-link' . ($key == 0 ? ' active' : '') . '" id="nav-' . $commande->getSlug() . '-tab" data-toggle="tab" href="#nav-' . $commande->getSlug() . '" role="tab" aria-controls="nav-' . $commande->getSlug() . '" aria-selected="true">' . $commande->getDentiste()->getUsername() . '</a>';
    }
    echo    '</div>'
        .'</nav>';
    
    echo '<div class="tab-content" id="nav-tabContent">';
    foreach ($commandes as $key => $commande) {
    ?>
        <div class="tab-pane fade<?= ($key == 0 ? ' show active' : '') ?>" id="nav-<?= $commande->getSlug(); ?>" role="tabpanel" aria-labelledby="nav-<?= $commande->getSlug(); ?>-tab">
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Nom & Prénom du dentiste :</div>
                <div class="col-6 font-weight-bold" id="username-dentiste"><?= $commande->getDentiste()->getUsername(); ?></div>
            </div>
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Cabinet :</div>
                <div class="col-6 font-weight-bold" id="username-cabinet"><?= $commande->getDentiste()->getCabinet(); ?></div>
            </div>
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Ville / Adresse :</div>
                <div class="col-6 font-weight-bold" id="address"><?= $commande->getDentiste()->getAdresse(); ?></div>
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
                            <?php $choix_protheses = $commande->getChoixProtheses(); ?>
                            <?php foreach($choix_protheses as $prot): ?>
                                <tr>
                                    <td id="username-patient"><?= $commande->getUsernamePatient(); ?></td>
                                    <td id="number-case"><?= $prot->getProthese()->getNumero(); ?></td>
                                    <td id="details-commande"><?= nl2br($router->getValue($prot->getDetailsCommande())); ?></td>
                                    <td id="modif"><?= nl2br($router->getValue($prot->getModifDemand())); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
        // echo '<div class="tab-pane fade show active" id="nav-' . $commande->getSlug() . '" role="tabpanel" aria-labelledby="nav-' . $commande->getSlug() . '-tab">...</div>';
    }
    echo '</div>';
}