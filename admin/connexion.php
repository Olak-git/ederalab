<?php
// name: connexion
// route: connexion

use src\Router\Router;

    include '../autoload.php';

    $router = new Router;
    // var_dump($router->getAdmin());
    // die();

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
        .text-ederalab {
            color: #2c4c64 !important;
        }
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

        .input-flex-group {
            position:relative;
        }
        .input-flex-group input {
            padding-right: 38px !important;
        }
        .input-flex-group-append {
            height:calc(3.875rem + 2px);position:absolute;right:4px;top:0px;width:30px;
        }
        .input-flex-group-text {
            display:flex;justify-content:center;align-items:center;width:100%;height:100%;
        }
    </style>
    <script src="../assets/js/toggle-password.js"></script>
</head>
<body style="background:#f0ecec;">
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height:100vh;">
        <div class="d-flex justify-content-center w-100">
            <div class="col-12 col-sm-8 col-md-6">
                <?= ''//'<p>Slug: ' . md5(password_hash(time(), 1)) . ', pass: ' . password_hash('default', 1) . '</p>'; ?>
                <!-- Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aspernatur rem deserunt culpa ipsam iste dolorum, corporis amet reiciendis, quia nostrum laboriosam consectetur quos perferendis architecto, quasi tempore nulla tenetur! Porro. -->
                <form method="post" class="w-100">
                    <div class="py-3 px-5 shadow" style="border-radius:1rem;">
                        <img src="<?= $router->getLogo(); ?>" alt="" style="width:80px;">

                        <div class="form-group mb-5 px-4">
                            <label for="inputAddress2" class="text-center d-block mb-4" style="color:#2c4c64;font-size:1.25rem;">Identifiant Prothésiste</label>
                            <div class="input-flex-group" id="login_password">
                            <input type="password" name="pass" class="form-control form-control-lg form-style shadow-none text-center" id="inputAddress2" placeholder="">
                                <a href="#" onclick="showPassword(this);" class="input-flex-group-append text-secondary border-0 show-password" data-type="hide">
                                    <span class="input-flex-group-text border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            <?= $router->errorHTML2('pass'); ?>
                        </div>

                    </div>
                    <p class="text-right pr-2 mt-2"><a href="reinitialisation-identifiant.php" class="text-ederalab">Identifiant oublié ?</a></p>

                    <div class="form-group text-center mt-5">
                        <button class="btn p-3 btn-ederalab" style="min-width:190px;border-radius:10px;">Connexion</button>
                    </div>
                    <input type="hidden" name="csrf" value="<?= password_hash('admin-signin', 1); ?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>