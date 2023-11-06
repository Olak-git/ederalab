
            <div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header text-white" style="background:#000;">
                            <h5 class="modal-title" id="exampleModalLabel">Commande</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="">
                                <span aria-hidden="true" style="color:#e00514;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Dentiste :</div>
                                <div class="col-6 font-weight-bold" id="username-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Description :</div>
                                <div class="col-6 font-weight-bold" id="username-cabinet">#undefined</div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-center">
                                    <table class="table table-bordered table-detail w-100">
                                        <thead>
                                            <tr>
                                                <td class="text-center">Nom du patient</td>
                                                <td class="text-center">Cas n°</td>
                                                <td class="text-center">Détails de la commande</td>
                                                <td class="text-center">Modif. demandée</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="username-patient">#undefined</td>
                                                <td id="number-case">#undefined</td>
                                                <td id="details-commande">#undefined</td>
                                                <td id="modif">#undefined</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="date mb-3 font-weight-bold">Date de réception : <span id="date-reception">02/01/2022</span></div>
                            <div class="date mt-4 font-weight-bold">Date de livraison prévue : <span id="date-reception">02/01/2022</span></div>

                            <?php if($code == 1): ?>
                                <!-- <div class="date mb-3 font-weight-bold">Date de réception de la commande : <span id="date-reception">02/01/2022</span></div> -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <a href="tel:+22546544" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Contacter le dentiste</a>
                                    <a href="<?= $router->getRoutes()->path('gestion_commande_choix_fournisseur'); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Choix du fournisseur</a>
                                </div>
                            <?php elseif($code == 2): ?>
                                <div class="date mb-5 mt-4 font-weight-bold">Date de livraison de la commande : <span id="date-livraison">02/01/2022</span></div>
                                <div class="text-center mb-3">
                                    <a href="<?= $router->getRoutes()->path('factures'); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">Faire la facture</a>
                                </div>
                            <?php elseif($code == 3): ?>
                                <!-- <div class="date mb-5 mt-4 font-weight-bold">Date de réception de la commande : <span id="date-reception">02/01/2022</span></div> -->
                                <div class="text-center mb-3">
                                    <a href="<?= $router->getRoutes()->path('gestion_commande_choix_fournisseur'); ?>" class="mb-3 small btn-link rounded-fit d-inline-block text-center p-2" style="width:150px;">choix du fournisseur</a>
                                </div>
                            <?php elseif($code == 4): ?>
                                <div class="date mt-4 font-weight-bold">Date de réception de la commande : <span id="date-reception">02/01/2022</span></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>