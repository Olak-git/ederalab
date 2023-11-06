<?php
// name: dentistes
// route: dentistes

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
        tr {
            background-color: transparent;
        }
        tr.bg {
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
    </style>
<?php
    ob_start();

    include 'layouts/_create-dentiste-modal.php';
    // include 'layouts/_edit-transporteur-modal.php';
    include 'layouts/_profil-dentiste-modal.php';
?>

    <div class="d-flex justify-content-center my-4">
        <div class="col-12 col-sm-11">
            <a href="<?= $router->getRoutes()->path('creer_compte_dentiste'); ?>" type="button" class="btn border-ederalab bg-ederalab text-white font-weight-bold shadow-none" style="padding:.6rem 1.25rem;font-size:13px;border-radius: .75rem;">Ajouter un Dentiste</a>
        </div>
    </div>

    <div class="conteneur">

        <div class="d-none d-md-block">
            <div class="d-flex justify-content-center mb-3">
                <div class="col-12 col-sm-11" style="overflow-x:auto;">
                    <table class="table table-bordere">
                        <tbody>
                            <?php foreach($dentistes as $dentiste): ?>
                                <tr class="bg">
                                    <td>
                                        <span class="d-flex align-items-center">
                                            <span class="d-flex justify-content-center align-items-center p-1 mr-2" style="background-color:#dee2e6;width:40px;min-width:40px;height:40px;border-radius:50%;">
                                                <img src="<?= $router->getAvatar($dentiste->getImage()); ?>" class="mw-100 mh-100">
                                            </span>
                                            <?= $dentiste->getUsername(); ?>
                                        </span>
                                    </td>
                                    <td><?= 'Phone'//$dentiste->getPhone(); ?></td>
                                    <td><?= $dentiste->getEmail(); ?></td>
                                    <td style="width:20px;">
                                        <div class="btn-group dropleft ml-aut">
                                            <a href="" type="button" class="text-dark dropdown-toggl" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                            </svg></a>

                                            <div class="dropdown-menu border-0 bg-ederalab">
                                                <a href="#" type="button" class="showDentisteProfil btn btn-block text-left text-white" 
                                                    data-toggle="modal" 
                                                    data-target="#profilDentisteModal" 
                                                    data-id="<?= $dentiste->getSlug(); ?>" 
                                                    data-username="<?= $dentiste->getUsername(); ?>"
                                                    data-cabinet="<?= $dentiste->getCabinet(); ?>"
                                                    data-email="<?= $dentiste->getEmail(); ?>" 
                                                    data-address="<?= $dentiste->getAdresse(); ?>" 
                                                    data-phone="<?= ''//$dentiste->getPhone(); ?>"
                                                    style="border-bottom:1px solid #fff;">Voir le profil</a>
                                                <a href="<?= $router->getRoutes()->path('dentiste', ['s' => $dentiste->getSlug()]); ?>" class="btn btn-block text-left text-white" style="border-bottom:1px solid #fff;">Voir toutes les commandes</a>
                                                <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste', 'dest' => $dentiste->getSlug()]); ?>" class="btn btn-block text-left text-white">Envoyer un message</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="p-1"></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-block d-md-none">
            <?php foreach($dentistes as $dentiste): ?>
                <div class="d-flex justify-content-center mb-3">
                    <div class="col-12 col-sm-11">
                        <div class="d-flex justify-content-between align-items-center bg-white p-1" style="border:1px solid silver;border-radius:1rem;min-height:60px;">
                            <a href="#" type="button" class="showDentisteProfil btn btn-block text-left text-dark shadow-none" 
                                data-toggle="modal" 
                                data-target="#profilDentisteModal" 
                                data-id="<?= $dentiste->getSlug(); ?>" 
                                data-username="<?= $dentiste->getUsername(); ?>"
                                data-cabinet="<?= $dentiste->getCabinet(); ?>"
                                data-email="<?= $dentiste->getEmail(); ?>" 
                                data-address="<?= $dentiste->getAdresse(); ?>" 
                                data-phone="<?= ''//$dentiste->getPhone(); ?>" 
                                style="width:calc(100% - 30px);">
                                <div class="word-break d-flex flex-wrap w-100">
                                    <div class="col-12 col-sm-6 col-md-8"><?= $dentiste->getUsername(); ?>, <?= $dentiste->getAdresse(); ?></div>
                                    <div class="col-12 col-sm-6 col-md-4"><?= ''//$dentiste->getPhone(); ?></div>
                                </div>
                            </a>
                            <div class="text-right px-0" style="width:30px;">
                                <div class="btn-group dropleft ml-aut">
                                    <a href="" type="button" class="text-dark dropdown-toggl" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    </svg></a>

                                    <div class="dropdown-menu border-0 bg-ederalab">
                                        <a href="#" type="button" class="showDentisteProfil btn btn-block text-left text-white" 
                                            data-toggle="modal" 
                                            data-target="#profilDentisteModal" 
                                            data-id="<?= $dentiste->getSlug(); ?>" 
                                            data-username="<?= $dentiste->getUsername(); ?>"
                                            data-cabinet="<?= $dentiste->getCabinet(); ?>"
                                            data-email="<?= $dentiste->getEmail(); ?>" 
                                            data-address="<?= $dentiste->getAdresse(); ?>" 
                                            data-phone="<?= ''//$dentiste->getPhone(); ?>"
                                            style="border-bottom:1px solid #fff;">Voir le profil</a>
                                        <a href="<?= $router->getRoutes()->path('dentiste', ['s' => $dentiste->getSlug()]); ?>" class="btn btn-block text-left text-white" style="border-bottom:1px solid #fff;">Voir toutes les commandes</a>
                                        <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste', 'dest' => $dentiste->getSlug()]); ?>" class="btn btn-block text-left text-white">Envoyer un message</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

        S('.showDentisteProfil').forEach(e => {
            e.onclick = function () {
                Z('#username-dentiste').textContent = this.getAttribute('data-username');
                Z('#cabinet-dentiste').textContent = this.getAttribute('data-cabinet');
                Z('#address-dentiste').textContent = this.getAttribute('data-address');
                Z('#email-dentiste').textContent = this.getAttribute('data-email');
                Z('#phone-dentiste').textContent = this.getAttribute('data-phone');
            }
        })
    </script>
<?php
    $script = ob_get_clean();

require 'base.php';