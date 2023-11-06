            <div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header text-white" style="background:#000;">
                            <!-- <h5 class="modal-title" id="exampleModalLabel"></h5> -->
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
                                <div class="col-6 pl-0">Nom & Prénom du dentiste :</div>
                                <div class="col-6 font-weight-bold" id="username-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Cabinet :</div>
                                <div class="col-6 font-weight-bold" id="username-cabinet">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Ville / Adresse :</div>
                                <div class="col-6 font-weight-bold" id="address">#undefined</div>
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

                            <?php if($clink == 1): ?>

                                <form method="post" action="" name="" class="" onsubmit="window.event.preventDefault();console.log(this)">
                                    <div class="text-center"><button type="butto" class="btn px-4 bg-ederalab text-white border-0" style="border-radius:.5rem;">Accepter la commande</button></div>
                                    <input type="hidden" name="csrf" value="<?= password_hash('edit-' . (isset($pre) ? strtolower($pre) : '') . 'categorie', 1); ?>">
                                </form>

                            <?php elseif($clink == 3): ?>

                                <div class="d-flex mb-3">
                                    <div class="col-6 pl-0">Heure d'expédition :</div>
                                    <div class="col-6 font-weight-bold" id="address">#undefined</div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="col-6 pl-0">Transporteur :</div>
                                    <div class="col-6 font-weight-bold" id="address">#undefined</div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>



    <?php /*if(isset($clink)): ?>
        <?php if($clink == 1 || $clink == 2): ?>
            <div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header text-white" style="background:#000;">
                            <!-- <h5 class="modal-title" id="exampleModalLabel"></h5> -->
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
                                <div class="col-6 pl-0">Nom & Prénom du dentiste :</div>
                                <div class="col-6 font-weight-bold" id="username-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Cabinet :</div>
                                <div class="col-6 font-weight-bold" id="username-cabinet">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Ville / Adresse :</div>
                                <div class="col-6 font-weight-bold" id="address">#undefined</div>
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

                            <?php if($clink == 1): ?>
                                <form method="post" action="" name="" class="" onsubmit="window.event.preventDefault();console.log(this)">
                                    <div class="text-center"><button type="butto" class="btn px-4 bg-ederalab text-white border-0" style="border-radius:.5rem;">Accepter la commande</button></div>
                                    <input type="hidden" name="csrf" value="<?= password_hash('edit-' . (isset($pre) ? strtolower($pre) : '') . 'categorie', 1); ?>">
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif($clink == 3): ?>
            <div class="modal fade" id="commandeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header text-white" style="background:#000;">
                            <!-- <h5 class="modal-title" id="exampleModalLabel"></h5> -->
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
                                <div class="col-6 pl-0">Cabinet :</div>
                                <div class="col-6 font-weight-bold" id="username-cabinet">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Ville / Adresse :</div>
                                <div class="col-6 font-weight-bold" id="address">#undefined</div>
                            </div>
                            <div class="mb-3">
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
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Heure d'expédition :</div>
                                <div class="col-6 font-weight-bold" id="address">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 pl-0">Transporteur :</div>
                                <div class="col-6 font-weight-bold" id="address">#undefined</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif;*/ ?>