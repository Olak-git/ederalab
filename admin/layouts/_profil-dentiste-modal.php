
            <div class="modal fade" id="profilDentisteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <div class="d-flex mb-3">
                                <div class="col-6 font-weight-bold pl-0">Nom & Prénom :</div>
                                <div class="col-6" style="font-weight:300;" id="username-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 font-weight-bold pl-0">Cabinet :</div>
                                <div class="col-6" style="font-weight:300;" id="cabinet-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 font-weight-bold pl-0">Ville/Adresse :</div>
                                <div class="col-6" style="font-weight:300;" id="address-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 font-weight-bold pl-0">Email :</div>
                                <div class="col-6" style="font-weight:300;" id="email-dentiste">#undefined</div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="col-6 font-weight-bold pl-0">Téléphone :</div>
                                <div class="col-6" style="font-weight:300;" id="phone-dentiste">#undefined</div>
                            </div>
                            <?php /*<form method="post" action="" name="" class="mt-4" onsubmit="window.event.preventDefault();console.log(this)">
                                <div class="text-center"><button type="butto" class="btn px-4 bg-ederalab text-white border-0 shadow-none" style="border-radius:.5rem;">Valider</button></div>
                                <input type="hidden" name="transporteur[id]">
                                <input type="hidden" name="csrf" value="<?= password_hash('edit-' . (isset($pre) ? strtolower($pre) : '') . 'categorie', 1); ?>">
                            </form>*/ ?>
                        </div>
                    </div>
                </div>
            </div>