<?php
// name: parametres
// route: parametres-([a-z]*)
// route: parametres-([a-z]*)-([a-z]*)

use src\Repository\ProtheseRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$hlink = 2;

$param_link = 1;
$protheses = [];
if(isset($_GET['param'])) {
    $param_link = 2;
    if(isset($_GET['add'])) {

    }
    $protheses = (new ProtheseRepository)->findAll();
}

    ob_start();
?>
    <style>
        main {
            padding-top: var(--nav-top-height);
            height: 100vh;
            padding-bottom: 0;
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
    </style>
<?php
    $style = ob_get_clean();

    ob_start();
?>

    <div class="main w-100 h-100 d-flex flex-wrap justify-content-between">

        <div class="col-12 col-md-6 p-0" style="border-right:1px solid #ddd;">
            <div class="card h-100 bg-light border-0 d-flex flex-column justify-content-between py-5">
                
                <div>
                    <h5 class="mb-4 pl-4">Générale</h5>
                    <div>
                        <a href="<?= $router->getRoutes()->path('parametres'); ?>" class="params-link <?= isset($param_link) && $param_link == 1 ? 'active' : '' ?> text-decoration-none">Compte</a>
                        <a href="signout.php" class="params-link text-decoration-none">Déconnexion</a>
                    </div>
                </div>

                <div>
                    <h5 class="mb-4 pl-4">Système</h5>
                    <div>
                        <a href="?param" class="params-link <?= isset($param_link) && $param_link == 2 ? 'active' : '' ?> text-decoration-none">Prothèses</a>
                        <a href="<?= $router->getRoutes()->path('gestion_commandes_recues'); ?>" class="params-link text-decoration-none">Gestion des commandes</a>
                        <a href="<?= $router->getRoutes()->path('chart'); ?>" class="params-link text-decoration-none">Rapport d'activité</a>
                        <a href="<?= $router->getRoutes()->path('message', ['user' => 'dentiste']); ?>" class="params-link text-decoration-none">Messagerie</a>
                        <a href="<?= $router->getRoutes()->path('tracking'); ?>" class="params-link text-decoration-none">Géolocalisation</a>
                        <a href="<?= $router->getRoutes()->path('factures'); ?>" class="params-link text-decoration-none">Facture</a>
                    </div>
                </div>


            </div>
        </div>

        <div class="col-12 col-md-6 p-0">
            <div class="card h-100 bg-light border-0" style="overflow-y:auto">
                <?php if(isset($param_link) && $param_link == 1): ?>
                    <form method="post" class="-100">
                        <div class="py-3 px-5" style="border-radius:1rem;">
                            <img src="<?= $router->getLogo(); ?>" alt="" style="width:80px;">
                            <div class="form-group mb- px-4">
                                <label for="inputAddress2" class="text-center d-block mb-0" style="color:#2c4c64;font-size:1.25rem;">Identifiant Prothésiste</label>
                                <label for="inputAddress2" class="text-center d-block mb-4 font-italic text-muted" style=""><small>(modifier)</small></label>
                                <input type="text" name="admin_update_id[identifiant]" class="form-control form-control-lg form-style shadow-none" id="inputAddress2" placeholder="Identifiant" value="<?= $router->getValPost(['admin_update_id', 'identifiant']); ?>" style="font-size:15px;">
                                <small id="" class="form-text text-muted">Sera mis à jour si vous remplissez le champ.</small>
                                <?= $router->errorHTML2('identifiant'); ?>
                            </div>
                            <div class="form-group mb- px-4">
                                <input type="email" name="admin_update_id[email]" class="form-control form-control-lg form-style shadow-none" id="inputAddress2" placeholder="email" value="<?= $router->getValPost(['admin_update_id', 'email']) !== '' ? $router->getValPost(['admin_update_id', 'email']) : $router->getAdmin()->getEmail(); ?>" style="font-size:15px;">
                                <small id="" class="form-text text-muted">Est obligatoire pour la réinitialisation de votre identifiant.</small>
                                <?= $router->errorHTML2('email'); ?>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn p-3 btn-ederalab bg-ederalab text-white" style="min-width:190px;border-radius:10px;">Enregistrer</button>
                        </div>
                        <input type="hidden" name="csrf" value="<?= password_hash('admin-update-id', 1); ?>">
                    </form>
                <?php elseif(isset($param_link) && $param_link == 2): ?>
                    <?php if(isset($_GET['add'])): ?>
                        <div class="p-3">
                            <h5 class="my-3">Ajouter un nouveau cas de prothèse</h5>
                            <form method="post" class="w-100">
                                <div class="p-4 shadow" style="border-radius:1rem;">
                                    <div class="form-group mb-" id="nom">
                                        <div class="d-flex align-items-center">
                                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="cas_prothese_nom" class="">Nom</label><span class="">:</span></div>
                                            <div class="col"><input type="text" name="cas_prothese[nom]" class="form-control rounded-pill shadow-none" id="cas_prothese_nom" placeholder="Nom" value="<?= $router->getValPost(['cas_prothese', 'nom']); ?>"></div>
                                        </div>
                                        <?= $router->errorHTML2('nom'); ?>
                                    </div>
                                    <div class="form-group mb-" id="numero">
                                        <div class="d-flex align-items-center">
                                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="cas_prothese_numero" class="">Cas n°</label><span class="">:</span></div>
                                            <div class="col"><input type="number" name="cas_prothese[numero]" class="form-control rounded-pill shadow-none" id="cas_prothese_numero" placeholder="Numero" value="<?= $router->getValPost(['cas_prothese', 'numero']); ?>"></div>
                                        </div>
                                        <?= $router->errorHTML2('numero'); ?>
                                    </div>
                                    <div class="form-group mb-" id="detail">
                                        <div class="d-flex align-items-start">
                                            <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="cas_prothese_detail" class="">Détails</label><span class="">:</span></div>
                                            <div class="col">
                                                <textarea name="cas_prothese[detail]" class="form-control shadow-none" id="cas_prothese_detail" rows="3" style="resize:none;line-height:normal;font-size:15px;"><?= $router->getValPost(['cas_prothese', 'detail']); ?></textarea>
                                            </div>
                                        </div>
                                        <?= $router->errorHTML2('detail'); ?>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4">
                                    <button class="btn p-2 btn-ederalab shadow-none" style="min-width:190px;border-radius:10px;">Ajouter</button>
                                </div>

                                <input type="hidden" name="csrf" value="<?= password_hash('admin-add-prothese', 1); ?>">
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="p-2">
                            <table class="table table-sm table-bordered">
                                <caption style="caption-side:top;">
                                    <span class="d-flex justify-content-between align-items-center">
                                        Prohèses 
                                        <a href="<?= $router->getRoutes()->path('parametres', ['param' => 's', 'add' => 'pro']); ?>" class="text-ederalab"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/>
                                            </svg>
                                        </a>
                                    </span>
                                </caption>
                                <thead>
                                    <tr class="bg-ederalab text-white">
                                        <th>Nom</th>
                                        <th>Cas n°</th>
                                        <th>Détails</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($protheses as $prothese): ?>
                                        <tr>
                                            <td><?= $prothese->getNom(); ?></td>
                                            <td><?= $prothese->getNumero(); ?></td>
                                            <td><?= $prothese->getDetail(); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

<?php
    $main = ob_get_clean();

    ob_start();
?>

<?php
    $script = ob_get_clean();

require 'base.php';