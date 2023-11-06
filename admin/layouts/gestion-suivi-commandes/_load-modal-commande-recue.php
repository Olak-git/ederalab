
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
            <div class="w-100">

                <div class="d-flex mb-3">
                    <div class="col-6 pl-0">Dentiste :</div>
                    <div class="col-6 font-weight-bold" id="username-dentiste"><?= $commande->getDentiste()->getUsername(); ?></div>
                </div>

                <div class="d-flex mb-3">
                    <div class="col-6 pl-0">Description :</div>
                    <div class="col-6 font-weight-bold" id="username-cabinet">
                        <p><?= nl2br($commande->getDescriptionSpecifiq()); ?></p>
                        <?php if(!empty($commande->getDescriptionLibre())): ?>
                            <p><?= nl2br($commande->getDescriptionLibre()); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-center">
                        <table class="table table-bordered w-100">
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

                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="date mb-3 font-weight-bold">Date de réception : <span id="date-reception"><?= (new \DateTime($commande->getDateEnvoie()))->format('d/m/Y'); ?></span></div>
                    <div class="date mb-3 font-weight-bold">Date de livraison prévue : <span id="date-reception"><?= (new \DateTime($commande->getDateReceptionPrevue()))->format('d/m/Y'); ?></span></div>
                </div>

                <div class="my-3"><span class="font-weight-bold mr-2">Validation :</span><?= $commande->getValide() == 0 ? '<span class="text-warning">En attente de validation</span>' : ($commande->getValide() == -1 ? '<span class="text-danger">Commande annulée</span>' : '<span class="text-success">Commande acceptée</span>'); ?></div>

                <?php if($commande->getValide() == 0): ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <form method="post" class="mb-3" data-uniq-s="<?= $commande->getId() ?>" onsubmit="setStateCommand(this, 'accept');//window.event.preventDefault();console.log(this)">
                            <div class="text-center">
                                <button type="butto" class="btn px-4 bg-ederalab text-white rounded-fit border-0 shadow-none">Accepter la commande</button>
                            </div>
                            <input type="hidden" name="accept_cmd[commande]" value="<?= $commande->getId(); ?>"/>
                            <input type="hidden" name="csrf" value="<?= password_hash('accept-command', 1); ?>">
                        </form>

                        <form method="post" class="mb-3" data-uniq-s="<?= $commande->getId() ?>" onsubmit="setStateCommand(this, 'cancel');//window.event.preventDefault();console.log(this)">
                            <div class="text-center">
                                <button type="butto" class="btn px-4 bg-ederalab text-white rounded-fit border-0 shadow-none">Refuser la commande</button>
                            </div>
                            <input type="hidden" name="cancel_cmd[commande]" value="<?= $commande->getId(); ?>"/>
                            <input type="hidden" name="csrf" value="<?= password_hash('cancel-command', 1); ?>">
                        </form>

                    </div>

                <?php endif; ?>
                
                <?php if($commande->getValide() == 1): ?>
                    <div class="mb-3 mt-3">
                        <span class="font-weight-bold mr-2">Livraison :</span>
                        <?php if($commande->getLivraison() == 0): ?>
                            <span class="text-warning" id="delivery-text-<?= $commande->getId(); ?>">En attente</span>
                        <?php elseif($commande->getLivraison() == 1): ?>
                            <span class="text-muted" id="delivery-text-<?= $commande->getId(); ?>">En cours...</span>
                        <?php elseif($commande->getLivraison() == 2): ?>
                            <span class="text-success" id="delivery-text-<?= $commande->getId(); ?>">Reçue</span>
                        <?php elseif($commande->getLivraison() == -1): ?>
                            <span class="text-danger" id="delivery-text">non reçue</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste', 'dest' => $commande->getDentiste()->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Contacter le dentiste</a>
                        <?php if($commande->getLivraison() == 0): ?>
                            <a href="<?= $router->getRoutes()->path('gestion_commande_choix_fournisseur', ['commande' => $commande->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">choix du fournisseur</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($commande->getLivraison() == 2): ?>
                    <div class="date mb-5 mt-4 font-weight-bold">Date de livraison de la commande : <span id="date-livraison"><?= (new \DateTime($commande->getDateLivraison()))->format('d/m/Y'); ?></span></div>

                    <div class="text-center mb-3">
                        <?php if(!$commande->getFacture()): ?>
                            <a href="<?= $router->getRoutes()->path('editer_facture', ['key' => $commande->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2">Envoyer la facture</a>
                        <?php else: ?>
                            <a href="<?= $router->getRoutes()->path('facture', ['key' => $commande->getFacture()->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2">Voir la facture</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($code == 1): ?>
                    <?php /*<div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste', 'dest' => $commande->getDentiste()->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Contacter le dentiste</a>
                        <a href="<?= $router->getRoutes()->path('gestion_commande_choix_fournisseur', ['commande' => $commande->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Choix du fournisseur</a>
                    </div>*/ ?>
                <?php elseif($code == 2): ?>
                    

                <?php elseif($code == 3): ?>
                    <?php /*<div class="text-center mb-3">
                        <a href="<?= $router->getRoutes()->path('gestion_commande_choix_fournisseur', ['commande' => $commande->getSlug()]); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">choix du fournisseur</a>
                    </div>*/ ?>
                <?php endif; ?>

            </div>
            <!-- Begin Main -->

        </div>

    </div>

<?php endforeach; ?>
</div>