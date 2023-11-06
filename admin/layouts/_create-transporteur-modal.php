
            <div class="modal fade" id="createTranspModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            <label for="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                                                    <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                                    <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                </svg> Identifiant</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="transporteur[identifiant]" id="transporteur_identifiant" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('identifiant'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Nom & Prénom</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="transporteur[nom]" id="transporteur_nom" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('nom'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Ville/Adresse</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="text" name="transporteur[adresse]" id="transporteur_adresse" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('adresse'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-row align-items-center">
                                        <div class="col-12 col-sm-6">
                                            <label for="">Téléphone</label>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="tel" name="transporteur[phone]" id="transporteur_phone" placeholder="" class="form-control rounded-pill shadow-none" />
                                            <?= $router->errorHTML2('phone'); ?>
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