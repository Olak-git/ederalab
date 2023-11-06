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
        .rounded-pill {
            border-radius:25rem;
        }
        #notifier, #notifications {
            position: fixed;top: 10px;right: 10px;background: #000;color: #ffffff;min-width:50px;max-width: 50%;padding: 1rem;z-index: 9;font-size: 15px;
            border-radius: 1rem;
        }
    </style>
</head>
<body style="background:#fff;">
    <a href="connexion.php" style="position:fixed;left:10px;top:20px;color:#000;"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg></a>

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
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height:100vh;">
        <div class="d-flex justify-content-center w-100">
            <div class="col-12 col-sm-8 col-md-6">
                <?= ''//'<p>Slug: ' . md5(password_hash(time(), 1)) . ', pass: ' . password_hash('default', 1) . '</p>'; ?>
                <!-- Lorem, ipsum dolor sit amet consectetur adipisicing elit. Aspernatur rem deserunt culpa ipsam iste dolorum, corporis amet reiciendis, quia nostrum laboriosam consectetur quos perferendis architecto, quasi tempore nulla tenetur! Porro. -->
                <form method="post" class="w-100 my-3">
                    <div class="p-4 shadow" style="border-radius:1rem;">
                        <div class="d-flex flex-wrap justify-content-between">
                            <img src="<?= $router->getLogo(); ?>" alt="" style="width:80px;">
                            <h6>Réinitialisation de mot de passe</h6>
                        </div>
                        <div class="form-group mb-">
                            <div class="d-flex align-items-center">
                                <div class="col-4 p-0"><label for="inputAddress2" class="" style="color:#2c4c64;">Adresse e-mail</label></div>
                                <div class="col"><input type="email" name="dentiste_reset_password[email]" class="form-control rounded-pill shadow-none" id="inputAddress2" placeholder="" value="<?= $router->getValPost(['dentiste_reset_password', 'email']) ?>"></div>
                            </div>
                            <?= $router->errorHTML2('email'); ?>
                        </div>
                    </div>

                    <div class="form-group text-center mt-5">
                        <button class="btn p-3 btn-ederalab" style="min-width:190px;border-radius:10px;">Envoyer</button>
                    </div>
                    <input type="hidden" name="csrf" value="<?= password_hash('reset-password', 1); ?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>