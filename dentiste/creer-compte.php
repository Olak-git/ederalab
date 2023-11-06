<?php
use src\Router\Router;

    include '../autoload.php';

    $router = new Router;

    $router->request();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="description" content="">
    <title>Ederalab<?= isset($title_child) ? ' - ' . $title_child : ''; ?></title>
    <link rel="shortcut icon" href="<?= $router->getLogo(); ?>">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .btn-ederalab {
            background-color: #2c4c64;
            border: 1px solid #2c4c64;
            color: #fff;
            text-decoration: none !important;
            font-weight:600;
            font-size: 18px;
        }
        .btn-ederalab:hover {
            background: #fff;
            color: #2c4c64;
        }
        .shadow {
            box-shadow: 0 0rem 1rem rgba(0,0,0,.15)!important;
        }
        input.form-control {
            font-size:15px;
        }
        .form-style {
            height: calc(3.875rem + 2px);
            padding: .5rem 1rem;
            font-size: 15px;
            line-height: 1.5;
            border-radius: .8rem;
        }
        input.form-style:focus {
            border-color:#2c4c64 !important;
        }
        .rounded-pill {
            border-radius:25rem;
        }
        .text-ederalab {
            color:#2c4c64;
        }
        label {
            margin-bottom: 0 !important;
        }
        .input-flex-group {
            position:relative;
        }
        .input-flex-group input {
            padding-right: 38px !important;
        }
        .input-flex-group-append {
            height:calc(1.4em + 0.75rem + 2px);position:absolute;right:4px;top:0px;width:30px;
        }
        .input-flex-group-text {
            display:flex;justify-content:center;align-items:center;width:100%;height:100%;
        }
    </style>
    <script src="../assets/js/toggle-password.js"></script>
</head>
<body style="background:#fff;">
    
    <a href="index.php" style="position:fixed;left:10px;top:20px;color:#000;"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg></a>

    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height:100vh;overflow-y:auto;">
        <div class="d-flex justify-content-center w-100">
            <div class="col-12 col-sm-8 col-md-6">
                <?= ''//'<p>Slug: ' . md5(password_hash(time(), 1)) . ', pass: ' . password_hash('default', 1) . '</p>'; ?>
                <!-- Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aspernatur rem deserunt culpa ipsam iste dolorum, corporis amet reiciendis, quia nostrum laboriosam consectetur quos perferendis architecto, quasi tempore nulla tenetur! Porro. -->
                <form method="post" class="w-100 my-3">
                    <div class="p-4 shadow" style="border-radius:1rem;">
                        <img src="<?= $router->getLogo(); ?>" alt="" style="width:70px;">
                        <div class="form-group mb-" id="nom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_nom" class="">Nom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="my_compte_dentiste[nom]" class="form-control rounded-pill shadow-none" id="compte_dentiste_nom" placeholder="Nom" value="<?= $router->getValPost(['my_compte_dentiste', 'nom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('nom'); ?>
                        </div>
                        <div class="form-group mb-" id="prenom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_prenom" class="">Prénom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="my_compte_dentiste[prenom]" class="form-control rounded-pill shadow-none" id="compte_dentiste_prenom" placeholder="Prénom" value="<?= $router->getValPost(['my_compte_dentiste', 'prenom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('prenom'); ?>
                        </div>
                        <div class="form-group mb-" id="cabinet">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_cabinet" class="">Cabinet</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="my_compte_dentiste[cabinet]" class="form-control rounded-pill shadow-none" id="compte_dentiste_cabinet" placeholder="Cabinet" value="<?= $router->getValPost(['my_compte_dentiste', 'cabinet']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('cabinet'); ?>
                        </div>
                        <div class="form-group mb-" id="adresse">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_adresse" class="">Ville/Adresse</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="my_compte_dentiste[adresse]" class="form-control rounded-pill shadow-none" id="compte_dentiste_adresse" placeholder="" value="<?= $router->getValPost(['my_compte_dentiste', 'adresse']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('adresse'); ?>
                        </div>
                        <div class="form-group mb-" id="email">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_email" class="">Adresse e-mail</label><span class="">:</span></div>
                                <div class="col"><input type="email" name="my_compte_dentiste[email]" class="form-control rounded-pill shadow-none" id="compte_dentiste_email" placeholder="e-mail" value="<?= $router->getValPost(['my_compte_dentiste', 'email']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('email'); ?>
                        </div>
                        <div class="form-group" id="password">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_pass" class="">Mot de passe</label><span class="">:</span></div>
                                <div class="col">
                                    <div class="input-flex-group" id="login_password">
                                        <input type="password" name="my_compte_dentiste[password]" class="form-control rounded-pill shadow-none" id="compte_dentiste_pass" placeholder="******">
                                        <a href="#" onclick="showPassword(this);" class="input-flex-group-append text-secondary border-0 show-password" data-type="hide">
                                            <span class="input-flex-group-text border-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?= $router->errorHTML2('password'); ?>
                        </div>
                        <div class="form-group" id="confirmation_password">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="compte_dentiste_conf" class="">Confirmation</label><span class="">:</span></div>
                                <div class="col"><input type="password" name="my_compte_dentiste[confirmation_password]" class="form-control rounded-pill shadow-none" id="compte_dentiste_pass" placeholder="Confirmer le mot de passe"></div>
                            </div>
                            <?= $router->errorHTML2('confirmation_password'); ?>
                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button class="btn p-2 btn-ederalab" style="min-width:190px;border-radius:10px;">Créer mon compte</button>
                    </div>
                    <input type="hidden" name="csrf" value="<?= password_hash('dentiste-signup', 1); ?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>