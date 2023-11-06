<?php
// name: partenaires
// route: partenaires

use src\Repository\TransporteurRepository;
use src\Router\Router;

require '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$transporteurs = (new TransporteurRepository)->findBy(['del' => 0]);

$alink = 6;

?>
    <style>
    </style>
<?php
    ob_start();

    include 'layouts/_create-transporteur-modal.php';
    include 'layouts/_edit-transporteur-modal.php';
    include 'layouts/_profil-transporteur-modal.php';
?>

    <div class="d-flex justify-content-center my-4">
        <div class="col-12 col-sm-11">
            <a href="" type="button" data-toggle="modal" data-target="#createTranspModal" class="btn border-ederalab bg-ederalab text-white font-weight-bold shadow-none callCreateModal" style="padding:.6rem 1.25rem;font-size:13px;border-radius: .75rem;">Ajouter un Fournisseur</a>
        </div>
    </div>

    <div>
        <?php foreach($transporteurs as $trans): ?>
            <div class="d-flex justify-content-center mb-3">
                <div class="col-12 col-sm-11">
                    <div class="d-flex justify-content-between align-items-center bg-white p-1" style="border:1px solid silver;border-radius:1rem;min-height:60px;">
                        <a href="#" type="button" class="showTransProfil text-dark text-decoration-none" data-toggle="modal" data-target="#profilTranspModal"  data-id="<?= $trans->getSlug(); ?>" data-username="<?= $trans->getUsername(); ?>" data-address="<?= $trans->getAdresse(); ?>" data-phone="<?= $trans->getPhone(); ?>" class="text-decoration-none text-dark" style="width:calc(100% - 30px);">
                            <div class="word-break d-flex flex-wrap w-100">
                                <div class="col-12 col-sm-6 col-md-8"><?= $trans->getUsername(); ?>, <?= $trans->getAdresse(); ?></div>
                                <div class="col-12 col-sm-6 col-md-4"><?= $trans->getPhone(); ?></div>
                            </div>
                        </a>
                        <div class="text-right px-0" style="width:30px;">
                            <div class="btn-group dropleft ml-aut">
                                <a href="" type="button" class="text-dark dropdown-toggl" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                </svg></a>

                                <div class="dropdown-menu border-0 bg-ederalab">
                                    <a href="<?= $router->getRoutes()->path('commandes'); ?>" class="btn btn-block text-white small text-left callUpdateModal" 
                                        data-toggle="modal" 
                                        data-target="#editTranspModal" 
                                        data-code="<?= $trans->getCodePlain(); ?>" 
                                        data-id="<?= $trans->getId(); ?>" 
                                        data-username="<?= $trans->getUsername(); ?>" 
                                        data-address="<?= $trans->getAdresse(); ?>" 
                                        data-phone="<?= $trans->getPhone(); ?>" 
                                        style="border-bottom:1px solid #fff;padding: .25rem .5rem !important;">Modifier</a>
                                    <form method="post" class="my-0">
                                        <button class="btn btn-block text-white small text-left text-decoration-none mt-1 bg-ederalab border-0" style="padding: .25rem .5rem !important;cursor:pointer;">Supprimer</button>
                                        <input type="hidden" name="del_transporteur[id]" value="<?= $trans->getId(); ?>"/>
                                        <input type="hidden" name="csrf" value="<?= password_hash('del-transporter', 1); ?>">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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