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
    </style>
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
                        <div class="form-group mb-" id="identifiant">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab">
                                    <label for="transporteur_signup_identifiant" class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                                            <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                            <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                        </svg> Identifiant
                                    </label><span class="">:</span>
                                </div>
                                <div class="col"><input type="text" name="transporteur_signup[identifiant]" class="form-control rounded-pill shadow-none" id="transporteur_signup_identifiant" placeholder="" value="<?= $router->getValPost(['transporteur_signup', 'identifiant']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('identifiant'); ?>
                        </div>
                        <div class="form-group mb-" id="nom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="transporteur_signup_nom" class="">Nom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="transporteur_signup[nom]" class="form-control rounded-pill shadow-none" id="transporteur_signup_nom" placeholder="Nom" value="<?= $router->getValPost(['transporteur_signup', 'nom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('nom'); ?>
                        </div>
                        <div class="form-group mb-" id="prenom">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="transporteur_signup_prenom" class="">Prénom</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="transporteur_signup[prenom]" class="form-control rounded-pill shadow-none" id="transporteur_signup_prenom" placeholder="Prénom" value="<?= $router->getValPost(['transporteur_signup', 'prenom']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('prenom'); ?>
                        </div>
                        <div class="form-group mb-" id="email">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="transporteur_signup_email" class="">Adresse e-mail</label><span class="">:</span></div>
                                <div class="col"><input type="email" name="transporteur_signup[email]" class="form-control rounded-pill shadow-none" id="transporteur_signup_email" placeholder="e-mail" value="<?= $router->getValPost(['transporteur_signup', 'email']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('email'); ?>
                        </div>
                        <div class="form-group mb-" id="adresse">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0 d-flex justify-content-between align-items-center text-ederalab"><label for="transporteur_signup_adresse" class="">Ville/Adresse</label><span class="">:</span></div>
                                <div class="col"><input type="text" name="transporteur_signup[adresse]" class="form-control rounded-pill shadow-none" id="transporteur_signup_adresse" placeholder="" value="<?= $router->getValPost(['transporteur_signup', 'adresse']); ?>"></div>
                            </div>
                            <?= $router->errorHTML2('adresse'); ?>
                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button class="btn p-2 btn-ederalab" style="min-width:190px;border-radius:10px;">Créer mon compte</button>
                    </div>
                    <input type="hidden" name="csrf" value="<?= password_hash('transporteur-signup', 1); ?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>