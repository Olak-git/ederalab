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
                                            <div class="col-6 font-weight-bold pl-0">Dentiste :</div>
                                            <div class="col-6" id="" style="font-weight:300;"><?= $commande->getNomDentiste(); ?></div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="col-6 font-weight-bold pl-0">Cabinet :</div>
                                            <div class="col-6" id="" style="font-weight:300;"><?= $commande->getCabinet(); ?></div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <div class="col-6 font-weight-bold pl-0">Adresse :</div>
                                            <div class="col-6" id="" style="font-weight:300;"><?= $commande->getDentiste()->getAdresse(); ?></div>
                                        </div>
                                        <?php /*<div class="mb-4">
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
                                        <div class="mb-3"><span class="font-weight-bold mr-2">Date de réception prévue de la commande :</span><span><?= (new \DateTime($commande->getDateReceptionPrevue()))->format('d/m/Y'); ?></div>*/ ?>

                                        <div class="mb-3"><span class="font-weight-bold mr-2">Date de réception prévue de la commande :</span><span><?= (new \DateTime($commande->getDateReceptionPrevue()))->format('d/m/Y'); ?></div>
                                    
                                    </div>
                                    <!-- Begin Main -->

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>