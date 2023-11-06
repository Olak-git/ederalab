<?php
// name: creer_compte_dentiste
// route: creer-compte-dentiste

use src\Repository\DentisteRepository;
use src\Repository\TransporteurRepository;
use src\Router\Router;

require '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$transporteurs = (new TransporteurRepository)->findBy(['del' => 0]);
$dentistes = (new DentisteRepository)->findAll();

$alink = 7;

?>
    <style>
        
    </style>
<?php
    ob_start();
?>

    <div class="d-flex justify-content-center my-4">
        <div class="col-12 col-sm-11 text-right">
            <a href="<?= $router->getRoutes()->path('dentistes'); ?>" class="back-button btn-lin d-inline-block text-center p-2 font-weight-bold text-dark border" style="width:80px;border-radius:.5rem;text-decoration:none;">Retour</a>
        </div>
    </div>

    <div class="conteneur">

        <div class="d-flex justify-content-center mb-3">
            <div class="col-12 col-sm-8 col-md-6">
                <form method="post" class="w-100">
                    <div class="p-4 shadow" style="border-radius:1rem;">
                        <div class="form-group mb-" id="nom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_nom" class="">Nom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="compte_dentiste[nom]" class="form-control rounded-pill shadow-none" id="compte_dentiste_nom" placeholder="Nom" value="<?= $router->getValPost(['compte_dentiste', 'nom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('nom'); ?>
                        </div>
                        <div class="form-group mb-" id="prenom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_prenom" class="">Prénom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="compte_dentiste[prenom]" class="form-control rounded-pill shadow-none" id="compte_dentiste_prenom" placeholder="Prénom" value="<?= $router->getValPost(['compte_dentiste', 'prenom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('prenom'); ?>
                        </div>
                        <div class="form-group mb-" id="cabinet">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_cabinet" class="">Cabinet</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="compte_dentiste[cabinet]" class="form-control rounded-pill shadow-none" id="compte_dentiste_cabinet" placeholder="Cabinet" value="<?= $router->getValPost(['compte_dentiste', 'cabinet']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('cabinet'); ?>
                        </div>
                        <div class="form-group mb-" id="adresse">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_adresse" class="">Ville/Adresse</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="compte_dentiste[adresse]" class="form-control rounded-pill shadow-none" id="compte_dentiste_adresse" placeholder="" value="<?= $router->getValPost(['compte_dentiste', 'adresse']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('adresse'); ?>
                        </div>
                        <div class="form-group mb-" id="email">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_email" class="">Adresse e-mail</label><span class="">:</span></div>
                                <div class="col"><input type="email" name="compte_dentiste[email]" class="form-control rounded-pill shadow-none" id="compte_dentiste_email" placeholder="e-mail" value="<?= $router->getValPost(['compte_dentiste', 'email']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('email'); ?>
                        </div>
                        <div class="form-group" id="password">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_pass" class="">Mot de passe</label><span class="">:</span></div>
                                <div class="col"><input type="password" name="compte_dentiste[password]" class="form-control rounded-pill shadow-none" id="compte_dentiste_pass" placeholder="******"></div>
                            </div>
                            <?= $router->errorHTML2('password'); ?>
                        </div>
                        <div class="form-group" id="confirmation_password">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_conf" class="">Confirmation</label><span class="">:</span></div>
                                <div class="col"><input type="password" name="compte_dentiste[confirmation_password]" class="form-control rounded-pill shadow-none" id="compte_dentiste_pass" placeholder="Confirmer le mot de passe"></div>
                            </div>
                            <?= $router->errorHTML2('confirmation_password'); ?>
                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button class="btn p-2 btn-ederalab shadow-none" style="min-width:190px;border-radius:10px;">Créer le compte</button>
                    </div>

                    <input type="hidden" name="csrf" value="<?= password_hash('admin-signup-dentiste', 1); ?>">
                </form>
            </div>
        </div>

    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>
    <script src="../assets/js/jquery.caret.js"></script>
    <script src="../assets/js/jquery.mobilePhoneNumber.js"></script>
    <script>
        S('.callCreateModal').forEach(e => {
            e.onclick = function () {
                removeErrorHTML2('identifiant');
                removeErrorHTML2('nom');
                removeErrorHTML2('adresse');
                removeErrorHTML2('phone');
            }
        })
        function formatPhoneNumber() {
            jQuery(function($){
                var input = $('[type=tel]')
                input.mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
                // input.bind('country.mobilePhoneNumber', function(e, country) {
                //     $('label.country').text(country || '')
                //     $('input.country').val(country || '')
                // })
            });   
        }
        formatPhoneNumber()

        const btn_toggles = document.querySelectorAll('.toggle')

        function hideToggleDiv(e) {
            Array.from(btn_toggles).forEach((a, i) => {
                if(e !== i) {
                    a.parentElement.lastElementChild.classList.add('d-none')
                }
            })                
        }

        Array.from(btn_toggles).forEach((a, i) => {
            a.onclick = function(event) {
                event.preventDefault()
                hideToggleDiv(i)
                this.parentElement.lastElementChild.classList.toggle('d-none')
            }
        })

        const validationForm = function(e, l = '') {
            let error = false;
            removeErrorHTML2(l + 'identifiant');
            removeErrorHTML2(l + 'nom');
            removeErrorHTML2(l + 'adresse');
            removeErrorHTML2(l + 'phone');
            const id = Z('#' + l + 'transporteur_identifiant')
            const nom = Z('#' + l + 'transporteur_nom')
            const adresse = Z('#' + l + 'transporteur_adresse')
            const phone = Z('#' + l + 'transporteur_phone')

            if(id.value.trim() == '') {
                const errorId = errorHTML2(l + 'identifiant', 'Est requis');
                errorId.classList.add('mt-1')
                id.insertAdjacentElement('afterend', errorId)
                error = true;
            }
            if(nom.value.trim() == '') {
                const errorNom = errorHTML2(l + 'nom', 'Est requis');
                errorNom.classList.add('mt-1')
                nom.insertAdjacentElement('afterend', errorNom)
                error = true;
            }
            if(adresse.value.trim() == '') {
                const errorAdr = errorHTML2(l + 'adresse', 'Est requis');
                errorAdr.classList.add('mt-1')
                adresse.insertAdjacentElement('afterend', errorAdr)
                error = true;
            }
            if(phone.value.trim() == '') {
                const errorPhone = errorHTML2(l + 'phone', 'Est requis');
                errorPhone.classList.add('mt-1')
                phone.insertAdjacentElement('afterend', errorPhone)
                error = true;
            } else {
                const reg = /^(\+){1}[0-9]+[ ]*/i
                if(!reg.test(phone.value.trim())) {
                    const errorPhone = errorHTML2(l + 'phone', 'Format invalide (Ex: +14151234567)');
                    errorPhone.classList.add('mt-1')
                    phone.insertAdjacentElement('afterend', errorPhone)
                    error = true;
                }
            }

            if(!error) {
                // const formData = new FormData(e)
                // fetchJson({url: 'async.php', formData: formData, element: e})
                e.submit();
            }
        }

        document.forms.new_transporteur.onsubmit = function (event) {
            event.preventDefault();
            validationForm(this);
        }

        document.forms.edit_transporteur.onsubmit = function (event) {
            event.preventDefault();
            validationForm(this, 'edit_');
        }

        S('.showTransProfil').forEach(e => {
            e.onclick = function () {
                Z('#username-transp').textContent = this.getAttribute('data-username');
                Z('#address-transp').textContent = this.getAttribute('data-address');
                Z('#phone-transp').textContent = this.getAttribute('data-phone');
            }
        })

        S('.callUpdateModal').forEach(e => {
            e.onclick = function () {
                removeErrorHTML2('edit_identifiant');
                removeErrorHTML2('edit_nom');
                removeErrorHTML2('edit_adresse');
                removeErrorHTML2('edit_phone');
                Z('#edit_transporteur_identifiant').value = this.getAttribute('data-code')
                Z('#edit_transporteur_nom').value = this.getAttribute('data-username')
                Z('#edit_transporteur_adresse').value = this.getAttribute('data-address')
                Z('#edit_transporteur_phone').value = this.getAttribute('data-phone')
                Z('#edit_transporteur_id').value = this.getAttribute('data-id')
            }
        })
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';