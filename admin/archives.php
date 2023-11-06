<?php 
// name: archives
// route: archives

use src\Repository\CommandeRepository;
use src\Router\Router;

include '../autoload.php';

$router = new Router;

$router->adminIsConnected();

$router->request();

$commandeRepository = new CommandeRepository;

// commandes
$archives_today = $commandeRepository->getOrdersArchivedToday();
$archives_yesterday = $commandeRepository->getOrdersArchivedYesterday();
$archives_a_long_time = $commandeRepository->getOrdersArchivedLongTime();

// var_dump($archives_a_long_time);
// die;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="description" content="">
    <title>Ederalab - archives</title>
    <link rel="shortcut icon" href="<?= $router->getLogo(); ?>">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <style>
        :root {
            --height-nav: 198px
        }
        .nav-fixed {
            background-color: #fff;
            border-bottom: 2px solid #eee;
            padding-bottom: 1.5rem;
            height: var(--height-nav);
            z-index: 1;
        }
        .nav-fixed .input-group {
            background-color: #f8f9fa;
            border-radius: 1rem;
            padding: .5rem;
            /* width: 40vw; */
            color: #fff;
            border: 1px solid silver;
        }
        .nav-fixed .input-group input {
            background-color: transparent;
            width:calc(100% - 36px);
            border: none;
            padding: 5px;
            color: gray;
            font-size: 14px;
        }
        .nav-fixed .input-group input:focus {
            box-shadow: none !important;
        }
        .nav-fixed .input-group .append-element {
            display: inline-block;
            width:34px;
            /* padding-right: 1rem; */
            border: 0;
            background-color: transparent;
            /* border:1px solid; */
        }
        .nav-fixed form {
            margin-top: 4rem;
        }
        main {
            min-height: 100vh;
            padding-top: calc(var(--height-nav) + 3rem);
            padding-bottom: 2rem;
        }
        .archive {
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            align-items: center;
            height: 60px;
            /* padding:1rem; */
            margin-bottom: .5rem;
            cursor: pointer;
        }
        .archive .title {
            font-weight: 700;
        }
        .shadow-s {
            box-shadow: 0 0 .25rem rgba(0,0,0,.075)!important;
        }
        h5 {
            font-weight:300;
        }
    </style>
</head>
<body>

    <nav class="nav-fixed position-fixed w-100" style="top:0;left:0;">
        <header class="position-relative d-flex justify-content-between align-items-center" style="padding-left:10rem;padding-right:2rem;border-bottom:2px solid #eee;height:60px;">
            <h5>Archives</h5>
            <a href="#" class="back-button btn-link d-inline-block text-center p-2 font-weight-bold text-dark border" style="width:80px;border-radius:.5rem;text-decoration:none;">Retour</a>
            <img src="<?= $router->getLogo(); ?>" alt="" width="65" class="position-absolute" style="top:13px;left:30px;" />
        </header>

        <form class="px-4">
            <div class="input-group d-flex justify-content-between align-items-center">
                <input type="search" placeholder="Recherche" class="form-control form-control-sm px-2" />
                <button class="append-element text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </nav>

    <main class="container-fluid border">

        <?php if(!empty($archives_today)): ?>
            <div class="mb-5">
                <h5 class="p-3">Aujourd'hui</h5>
                <?php foreach($archives_today as $cmd): ?>
                    <div class="archive border shadow-s">
                        <div class="title col col-auto my-1">Commande archivée</div>
                        <div class="col my-1 text-center"><?= $cmd->getDentiste()->getUsername() . ', ' . $cmd->getUsernamePatient(); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($archives_yesterday)): ?>
            <div class="mb-5">
                <h5 class="p-3">Hier</h5>
                <?php foreach($archives_yesterday as $cmd): ?>
                    <div class="archive border shadow-s">
                        <div class="title col col-auto my-1">Commande archivée</div>
                        <div class="col my-1 text-center"><?= $cmd->getDentiste()->getUsername() . ', ' . $cmd->getUsernamePatient(); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
            
        <?php if(!empty($archives_a_long_time)): ?>
            <div class="mb-5">
                <h5 class="p-3">Ancien</h5>
                <?php foreach($archives_a_long_time as $cmd): ?>
                    <div class="archive border shadow-s">
                        <div class="title col col-auto my-1">Commande archivée</div>
                        <div class="col my-1 text-center"><?= $cmd->getDentiste()->getUsername() . ', ' . $cmd->getUsernamePatient(); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <script>
        document.querySelector('.back-button').onclick = function (event) {
            event.preventDefault();
            let href = this.getAttribute('href')
            if(href !== '' && href !== '#') {
                window.location = href
            } else {
                window.history.go(-1);
            }
        }
    </script>

</body>
</html>