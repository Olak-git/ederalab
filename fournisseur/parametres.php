<?php
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->transporteurIsConnected();

$router->request();

$param_link = 1;
if(isset($_GET['params'])) {
    $param_link = $_GET['params'];
}

?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <?php include '../layouts/_head-base.php'; ?>
    <style>
        :root {
            --pal: #304c64;
        }
        .rounded-pill {
            border-radius: 25rem;
        }
        main {
            height: 100vh;
            /* border:1px solid red; */
        }
        .params-header-left {
            position: absolute;
            top:0;left:0;
            width: 100%;
            height:80px;
            background-color: #fff;
        }
        .params-header-right {
            position: absolute;
            top:0;left:0;
            height:50px;
            width: 100%;
            background-color: #fff;
        }
        a.params-link {
            background-color: #fff;
            border-radius: .25rem;
            padding:.55rem 1rem;
            display: block;
            width: 250px;
            color:#000;
            margin-bottom: .5rem;
            border: 1px solid #fff;
        }
        a.params-link.active {
            background-color: var(--pal);
            color: #fff;
            border-color: var(--pal);
        }
        a.params-link:hover {
            border-color: var(--pal);
        }
        .text-decoration-none {
            text-decoration: none !important;
        }
        #notifier, #notifications {
            position: fixed;top: 10px;right: 10px;background: #000;color: #ffffff;min-width:50px;max-width: 50%;padding: 1rem;z-index: 9;font-size: 15px;
            border-radius: 1rem;
        }
        @media screen and (max-width: 767px) {
            main {
                height:auto;
                min-height: 100vh;
            }
            .params-header-left, .params-header-right {
                position: sticky;
            }
        }
        .zhg-image {
            min-width:100px;
            max-width: 100px;
            width:100px;
            height:100px;
            padding:1.2rem;
            border-radius:50%;
            background-color:#ccc;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script src="../assets/js/async.js"></script>
</head>
<body>

    <?php if($router->hasError()): ?>
        <div id="notifier">
            <a type="button" onclick="hideNotifier();" style="cursor:pointer;position:absolute;top:5px;right:1rem;color:#ff2222;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></a>
            <div class="mt-2">
                <?php foreach($router->getError() as $note): ?>
                    <span class="d-flex justify-content-start align-items-start w-100">
                        <span class="d-inline-block pt-1 mr-1" style="line-height:.5;"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                            <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
                        </svg></span>
                        <span class="d-block"><?= $note; ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if($router->getShowNotification()): ?>
        <div id="notifications" class="<?= $router->getNotificationColor(); ?>">
            <a type="button" onclick="hideNotification();" style="cursor:pointer;position:absolute;top:5px;right:1rem;color:#ffffff;"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg></a>
            <div class="mt-2">
                <?php foreach($router->getNotifications() as $note): ?>
                    <span class="d-flex justify-content-start align-items-start w-100">
                        <!-- <span class="d-inline-block pt-1 mr-1" style="line-height:.5;"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-asterisk" viewBox="0 0 16 16">
                            <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
                        </svg></span> -->
                        <span class="d-block"><?= $note; ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <main class="d-flex flex-wrap justify-content-between">

        <div class="col-12 col-md-6 h-100 p-0 position-relative" style="border-right:1px solid #ddd;">

            <div class="card h-100 bg-ligh border-0">
                
                <div class="params-header-left" style="">
                    <div class="d-flex justify-content-center align-items-center" style="position:absolute;top:0;left:0;width:100px;height:100%;">
                        <img src="<?= $router->getLogo(); ?>" style="width:50px;height:80%;">
                    </div>
                    <h5 class="text-center">Paramètres</h5>
                </div>

                <div class="p-3 d-flex flex-column justify-content-between" style="height:calc(100% - 80px);overflow-y:auto;margin-top:5rem;">

                    <div class="" style="">
                        <h5 class="mb-4 pl-4">Générale</h5>
                        <div>
                            <a href="parametres.php?params=1" class="params-link <?= isset($param_link) && $param_link == 1 ? 'active' : '' ?> text-decoration-none">Notifications</a>
                            <a href="message.php" class="params-link text-decoration-none">Messagerie</a>
                            <a href="map.php" class="params-link text-decoration-none">Map</a>
                            <a href="parametres.php?params=2" class="params-link <?= isset($param_link) && $param_link == 2 ? 'active' : '' ?> text-decoration-none">Compte</a>
                            <a href="a-savoir.php" class="params-link text-decoration-none">A propos</a>
                            <a href="signout.php" class="params-link text-decoration-none">Déconnexion</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-12 col-md-6 p-0 h-100 position-relative">

            <div class="card h-100 bg-ligh border-0" style="overflow-y:auto">

                <div class="params-header-right border-bottom d-flex text-right px-3">
                    <a href="accueil.php" class="d-flex justify-content-center align-items-center text-decoration-none text-dark font-weight-bold ml-auto">Retour</a>
                </div>

                <div class="mt-5 p-3" style="height:calc(100% - 50px);overflow-y:auto;">
                    <?php if(isset($param_link) && $param_link == 1): ?>
                        <h5>Notifications</h5>

                    <?php elseif(isset($param_link) && $param_link == 2): ?>

                        <form method="post" enctype="multipart/form-data" class="w-100 mt-5">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <span class="position-relative zhg-image">
                                    <img src="<?= $router->getAvatar($router->getTransporteur()->getImage()); ?>" alt="" class="mw-100 mh-100" />
                                    <a href="#" type="button" class="btn-primary d-flex justify-content-center align-items-center" style="position:absolute;bottom:0px;right:0;width:35px;height:35px;border-radius:50%;overflow:hidden;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16" style="width:40%;height:40%;">
                                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                            <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z"/>
                                        </svg>
                                        <input type="file" name="avatar" onchange="fileProfilChange(this);" accept=".png, .jpeg, .jpg, .gif" style="border:1px solid red;width:100%;height:100%;position:absolute;top:0px;left:0;opacity:0;">
                                    </a>
                                </span>
                            </div>

                            <div class="p-4 shadow" style="border-radius:1rem;">
                                <div class="form-group mb-" id="identifiant">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab">
                                            <label for="compte_transporteur_nom" class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                                                    <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                                    <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                </svg> Identifiant
                                            </label><span class="">:</span>
                                        </div>
                                        <div class="col"><input type="text" name="upd_compte_transporteur[identifiant]" class="form-control rounded-pill shadow-none" id="compte_transporteur_identifiant" placeholder="" value="<?= $router->getValPost(['upd_compte_transporteur', 'identifiant']) !== '' ? $router->getValPost(['upd_compte_transporteur', 'identifiant']) : $router->getTransporteur()->getCodePlain(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('identifiant'); ?>
                                </div>
                                <div class="form-group mb-" id="nom">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_transporteur_prenom" class="">Nom</label><span class="">:</span></div>
                                        <div class="col"><input type="text" name="upd_compte_transporteur[nom]" class="form-control rounded-pill shadow-none" id="compte_transporteur_prenom" placeholder="Prénom" value="<?= $router->getValPost(['upd_compte_transporteur', 'nom']) !== '' ? $router->getValPost(['upd_compte_transporteur', 'nom']) : $router->getTransporteur()->getNom(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('nom'); ?>
                                </div>
                                <div class="form-group mb-" id="prenom">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_transporteur_prenom" class="">Prénom</label><span class="">:</span></div>
                                        <div class="col"><input type="text" name="upd_compte_transporteur[prenom]" class="form-control rounded-pill shadow-none" id="compte_transporteur_prenom" placeholder="Prénom" value="<?= $router->getValPost(['upd_compte_transporteur', 'prenom']) !== '' ? $router->getValPost(['upd_compte_transporteur', 'prenom']) : $router->getTransporteur()->getPrenom(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('prenom'); ?>
                                </div>
                                <div class="form-group mb-" id="email">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_transporteur_email" class="">Email</label><span class="">:</span></div>
                                        <div class="col"><input type="text" name="upd_compte_transporteur[email]" class="form-control rounded-pill shadow-none" id="compte_transporteur_email" placeholder="Email" value="<?= $router->getValPost(['upd_compte_transporteur', 'email']) !== '' ? $router->getValPost(['upd_compte_transporteur', 'email']) : $router->getTransporteur()->getEmail(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('email'); ?>
                                </div>
                                <div class="form-group mb-" id="adresse">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_transporteur_adresse" class="">Ville/Adresse</label><span class="">:</span></div>
                                        <div class="col"><input type="text" name="upd_compte_transporteur[adresse]" class="form-control rounded-pill shadow-none" id="compte_transporteur_adresse" placeholder="" value="<?= $router->getValPost(['compte_transporteur', 'adresse']) !== '' ? $router->getValPost(['compte_transporteur', 'adresse']) : $router->getTransporteur()->getAdresse(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('adresse'); ?>
                                </div>
                                <div class="form-group mb-" id="phone">
                                    <div class="d-flex align-items-center">
                                        <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_transporteur_phone" class="">Tél.</label><span class="">:</span></div>
                                        <div class="col"><input type="tel" name="upd_compte_transporteur[phone]" class="form-control rounded-pill shadow-none" id="compte_transporteur_phone" placeholder="" value="<?= $router->getValPost(['upd_compte_transporteur', 'phone']) !== '' ? $router->getValPost(['upd_compte_transporteur', 'phone']) : $router->getTransporteur()->getPhone(); ?>"></div>
                                    </div>
                                    <?= $router->errorHTML2('phone'); ?>
                                </div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button class="btn p-2 btn-ederalab shadow-none" style="min-width:190px;border-radius:10px;">Enregistrer</button>
                            </div>

                            <input type="hidden" name="csrf" value="<?= password_hash('update-account-transporteur', 1); ?>">
                        </form>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <script src="../assets/js/vendor.bundle.base.js"></script>
    <script src="../assets/js/jquery.caret.js"></script>
    <script src="../assets/js/jquery.mobilePhoneNumber.js"></script>
    <script>
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
        if(document.querySelector('[type=tel]')) {
            formatPhoneNumber()
        }
        function createProfilThumbnail(file) {
            let reader = new FileReader();
            reader.onload = function() {
                document.querySelector('.zhg-image img').src = this.result
            };
            reader.readAsDataURL(file);
        }
        const fileProfilChange =  function(e) {
            const allowedTypes = ['png', 'jpg', 'jpeg', 'gif'];
            var files = e.files,
            filesLen = files.length,
            imgType;
            for (var i = 0 ; i < filesLen ; i++) {
                imgType = files[i].name.split('.');
                imgType = imgType[imgType.length - 1];
                if(allowedTypes.indexOf(imgType.toLowerCase()) != -1) {
                    createProfilThumbnail(files[i]);
                }
            }
        };
    </script>
</body>
</html>