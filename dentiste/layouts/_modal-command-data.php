                    <div class="accordion" id="accordionExample">

                        <?php foreach ($commandes as $key => $commande): ?>

                            <div class="card border-0 ">

                                <div class="card-header" id="heading<?= $commande->getId(); ?>">
                                    <h5 class="mb-0">
                                        <button class="btn btn-block btn-link word-break" type="button" data-toggle="collapse" data-target="#collapse<?= $commande->getId(); ?>" aria-expanded="false" aria-controls="collapse<?= $commande->getId(); ?>">CO-<?= strtoupper($commande->getSlug()); ?></button>
                                    </h5>
                                </div>

                                <div id="collapse<?= $commande->getId(); ?>" class="collapse" aria-labelledby="heading<?= $commande->getId(); ?>" data-parent="#accordionExample">

                                    <!-- Begin Main -->
                                    <div class="w-100 border-bottom py-3">

                                        <div class="d-flex mb-3">
                                            <div class="col-6 font-weight-bold pl-0">Client :</div>
                                            <div class="col-6" id="" style="font-weight:300;"><?= $commande->getUsernamePatient(); ?></div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="col-6 font-weight-bold pl-0">Description :</div>
                                            <div class="col-6" id="" style="font-weight:300;"><?= nl2br($commande->getDescriptionSpecifiq()); ?></div>
                                        </div>
                                        <div class="mb-4">
                                            <div>
                                                <table class="table table-bordered table-details">
                                                    <thead>
                                                        <tr>
                                                            <td class="text-center">Nom du client</td>
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
                                        
                                        <div class="mb-3"><span class="font-weight-bold mr-2">Date d'envoie de la commande :</span><span><?= (new \DateTime($commande->getDateEnvoie()))->format('d/m/Y'); ?></span></div>
                                        <div class="mb-3"><span class="font-weight-bold mr-2">Date de réception prévue de la commande :</span><span><?= (new \DateTime($commande->getDateReceptionPrevue()))->format('d/m/Y'); ?></div>
                                        <div class="mb-3 mt-3"><span class="font-weight-bold mr-2">Validation :</span><?= $commande->getValide() == 0 ? '<span class="text-warning">En attente de validation</span>' : ($commande->getValide() == -1 ? '<span class="text-danger">Commande refusée</span>' : '<span class="text-success">Commande acceptée</span>'); ?></div>
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
                                            <?php if($commande->getLivraison() == 1): ?>
                                                <div class="d-flex">
                                                    <form method="post" class="mt-2" data-uniq-s="<?= $commande->getId(); ?>" onsubmit="setStateDelivery(this)">
                                                        <button class="btn btn-sm btn-success">reçue</button>
                                                        <input type="hidden" name="delivery[command]" value="<?= $commande->getSlug(); ?>">
                                                        <input type="hidden" name="delivery[v]" value="2">
                                                        <input type="hidden" name="csrf" value="<?= password_hash('delivery-command', 1) ?>">
                                                    </form>
                                                    <?php if(strtotime($commande->getDateReceptionPrevue()) <= strtotime(date('Y-m-d'))): ?>
                                                        <span class="mx-2"></span>
                                                        <form method="post" class="mt-2" data-uniq-s="<?= $commande->getId(); ?>" onsubmit="setStateDelivery(this)">
                                                            <button class="btn btn-sm btn-primary">non reçue</button>
                                                            <input type="hidden" name="delivery[command]" value="<?= $commande->getSlug(); ?>">
                                                            <input type="hidden" name="delivery[v]" value="-1">
                                                            <input type="hidden" name="csrf" value="<?= password_hash('delivery-command', 1) ?>">
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="small text-muted">Veuillez indiquer si vous avez reçu votre commande</div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    
                                    </div>
                                    <!-- Begin Main -->

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>