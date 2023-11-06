
            <div class="modal fade" id="createDentisteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
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
                            <form method="post" action="" name="new_transporteur" data-action="new-transporteur" class="">
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Nom</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[nom]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('nom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Prénom</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[prenom]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('prenom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Cabinet</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[cabinet]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('prenom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Adresse</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[adresse]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('prenom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Email</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[email]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('prenom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Mot de passe</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="compte_dentiste[password]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('prenom'); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-5"><button type="butto" class="btn px-4 py-2 bg-ederalab text-white border-0" style="border-radius:.5rem;">Valider</button></div>
                                <input type="hidden" name="csrf" value="<?= password_hash('new-transporter', 1); ?>">
                            </form>
                        </div>
                    </div>
                </div>
            </div>