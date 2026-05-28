
<div class="accordion" id="accordionExample">
<?php foreach ($commandes as $key => $commande): ?>

    <div class="card border-0">

        <div class="card-header" id="heading<?= $commande["id"]; ?>">
            <h5 class="mb-0">
                <button class="btn btn-block btn-link word-break" type="button" data-toggle="collapse" data-target="#collapse<?= $commande["id"]; ?>" aria-expanded="false" aria-controls="collapse<?= $commande["id"]; ?>">CO-<?= strtoupper($commande["slug"]); ?></button>
            </h5>
        </div>

        <div id="collapse<?= $commande["id"]; ?>" class="collapse" aria-labelledby="heading<?= $commande["id"]; ?>" data-parent="#accordionExample">

            <!-- Begin Main -->
            <div class="w-100">

                <div class="d-flex mb-3">
                    <div class="col-6 pl-0">Dentiste :</div>
                    <div class="col-6 font-weight-bold" id="username-dentiste"><?= $router->getUsername($commande["nom_dentiste"], $commande["prenom_dentiste"]); ?></div>
                </div>

                <div class="d-flex mb-3">
                    <div class="col-6 pl-0">Description :</div>
                    <div class="col-6 font-weight-bold" id="username-cabinet">
                        <p><?= nl2br($commande["description_specifiq"]); ?></p>
                        <?php if(!empty($commande["description_libre"])): ?>
                            <p><?= nl2br($commande["description_libre"]); ?></p>
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
                                <?php //$choix_protheses = $commande->getChoixProtheses(); 
                                    $choix_protheses = $router->getDb()->query(
                                        "SELECT cP.*, p.numero numero_prothese
                                        FROM choix_prothese cP
                                        INNER JOIN commande c
                                        ON cP.commande = c.id
                                        INNER JOIN prothese p
                                        ON cP.prothese = p.id
                                        WHERE cP.commande=:cmd", ["cmd" => $commande["id"]]
                                    )->fetchAll();
                                ?>
                                <?php foreach($choix_protheses as $prot): ?>
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

                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="date mb-3 font-weight-bold">Date de réception : <span id="date-reception"><?= (new \DateTime($commande["date_envoie"]))->format('d/m/Y'); ?></span></div>
                    <div class="date mb-3 font-weight-bold">Date de livraison prévue : <span id="date-reception"><?= (new \DateTime($commande["date_reception_prevue"]))->format('d/m/Y'); ?></span></div>
                </div>

                <div class="my-3"><span class="font-weight-bold mr-2">Validation :</span><?= $commande["valide"] == 0 ? '<span class="text-warning">En attente de validation</span>' : ($commande["valide"] == -1 ? '<span class="text-danger">Commande annulée</span>' : '<span class="text-success">Commande acceptée</span>'); ?></div>

                <?php if($commande["valide"] == 0): ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <form method="post" class="mb-3" data-uniq-s="<?= $commande["id"] ?>" onsubmit="setStateCommand(this, 'accept');//window.event.preventDefault();console.log(this)">
                            <div class="text-center">
                                <button type="butto" class="btn px-4 bg-ederalab text-white rounded-fit border-0 shadow-none">Accepter la commande</button>
                            </div>
                            <input type="hidden" name="accept_cmd[commande]" value="<?= $commande["id"]; ?>"/>
                            <input type="hidden" name="csrf" value="<?= password_hash('accept-command', 1); ?>">
                        </form>

                        <form method="post" class="mb-3" data-uniq-s="<?= $commande["id"] ?>" onsubmit="setStateCommand(this, 'cancel');//window.event.preventDefault();console.log(this)">
                            <div class="text-center">
                                <button type="butto" class="btn px-4 bg-ederalab text-white rounded-fit border-0 shadow-none">Refuser la commande</button>
                            </div>
                            <input type="hidden" name="cancel_cmd[commande]" value="<?= $commande["id"]; ?>"/>
                            <input type="hidden" name="csrf" value="<?= password_hash('cancel-command', 1); ?>">
                        </form>

                    </div>

                <?php endif; ?>
                
                <?php if($commande["valide"] == 1): ?>
                    <div class="mb-3 mt-3">
                        <span class="font-weight-bold mr-2">Livraison :</span>
                        <?php if($commande["livraison"] == 0): ?>
                            <span class="text-warning" id="delivery-text-<?= $commande["id"]; ?>">En attente</span>
                        <?php elseif($commande["livraison"] == 1): ?>
                            <span class="text-muted" id="delivery-text-<?= $commande["id"]; ?>">En cours...</span>
                        <?php elseif($commande["livraison"] == 2): ?>
                            <span class="text-success" id="delivery-text-<?= $commande["id"]; ?>">Reçue</span>
                        <?php elseif($commande["livraison"] == -1): ?>
                            <span class="text-danger" id="delivery-text">non reçue</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="message.php?user=dentiste&dest=<?= $commande["slug_dentiste"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Contacter le dentiste</a>
                        <?php if($commande["livraison"] == 0): ?>
                            <a href="gestion-suivi-commande-fournisseur.php?commande=<?= $commande["slug"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">choix du fournisseur</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if($commande["livraison"] == 2): ?>
                    <div class="date mb-5 mt-4 font-weight-bold">Date de livraison de la commande : <span id="date-livraison"><?= $commande["date_livraison"] ? (new \DateTime($commande["date_livraison"]))->format('d/m/Y') : ""; ?></span></div>

                    <div class="text-center mb-3">
                        <?php 
                            $facture = $db->findOneBy("facture", ['commande' => $commande["id"]]);
                        if(!$facture): ?>
                            <a href="editer-facture.php?key=<?= $commande["slug"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2">Envoyer la facture</a>
                        <?php else: ?>
                            <a href="facture.php?key=<?= $facture["slug"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2">Voir la facture</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($code == 1): ?>
                    <?php /*<div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="message.php?user=dentiste&dest=<?= $commande["slug_dentiste"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Contacter le dentiste</a>
                        <a href="gestion-suivi-commande-fournisseur.php?commande=<?= $commande["slug"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Choix du fournisseur</a>
                    </div>*/ ?>
                <?php elseif($code == 2): ?>
                    

                <?php elseif($code == 3): ?>
                    <?php /*<div class="text-center mb-3">
                        <a href="gestion-suivi-commande-fournisseur.php?commande=<?= $commande["slug"]; ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">choix du fournisseur</a>
                    </div>*/ ?>
                <?php endif; ?>

            </div>
            <!-- Begin Main -->

        </div>

    </div>

<?php endforeach; ?>
</div>