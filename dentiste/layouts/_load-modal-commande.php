
<div class="accordion" id="accordionExample">
<?php foreach ($commandes as $key => $commande): ?>

    <div class="card border-0">

        <div class="card-header" id="heading<?= $commande->getId(); ?>">
            <h5 class="mb-0">
                <button class="btn btn-block btn-link word-break" type="button" data-toggle="collapse" data-target="#collapse<?= $commande->getId(); ?>" aria-expanded="false" aria-controls="collapse<?= $commande->getId(); ?>">CO-<?= strtoupper($commande->getSlug()); ?></button>
            </h5>
        </div>

        <div id="collapse<?= $commande->getId(); ?>" class="collapse" aria-labelledby="heading<?= $commande->getId(); ?>" data-parent="#accordionExample">

    <!-- Begin Main -->
    <div class="w-100 border-bottom pt-3">

        <div class="d-flex mb-3">
            <div class="col-6 pl-0">Dentiste :</div>
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
                                <td id="number-case"><?= $prot->getProthese->getNumero(); ?></td>
                                <td id="details-commande"><?= nl2br($router->getValue($prot->getDetailsCommande())); ?></td>
                                <td id="modif"><?= nl2br($router->getValue($prot->getModifDemand())); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if($clink == 1 && $commande->getValide() == 0): ?>

            <form method="post" action="" name="" class="" onsubmit="console.log(this);//window.event.preventDefault();console.log(this)">
                <div class="text-center">
                    <button type="butto" class="btn px-4 bg-ederalab text-white border-0 shadow-none" style="border-radius:.5rem;">Accepter la commande</button>
                </div>
                <input type="hidden" name="accept_cmd[commande]" value="<?= $commande->getId(); ?>"/>
                <input type="hidden" name="csrf" value="<?= password_hash('accept-command', 1); ?>">
            </form>

        <?php elseif($clink == 3): ?>

            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Heure d'expédition :</div>
                <div class="col-6 font-weight-bold" id="address">#undefined</div>
            </div>
            <div class="d-flex mb-3">
                <div class="col-6 pl-0">Transporteur :</div>
                <div class="col-6 font-weight-bold" id="address"><?= $commande->getTransporteur() ? $commande->getTransporteur()->getUsername() : '#undefined'; ?></div>
            </div>

        <?php endif; ?>

    </div>
    <!-- Begin Main -->

        </div>

    </div>

<?php endforeach; ?>
</div>