<?php
// name: home
use src\Router\Router;

include '../autoload.php';

$router = new Router;
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="description" content="">
    <title>Amanou-Tech<?= isset($title_child) ? ' - ' . $title_child : ''; ?></title>
    <link rel="shortcut icon" href="<?= $router->getLogo(); ?>">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .btn-ederalab {
            background-color: #2c4c64;
            border: 1px solid #2c4c64;
            color: #fff;
            text-decoration: none !important;
            font-weight:600;
        }
        .btn-ederalab:hover {
            background: #fff;
            color: #2c4c64;
        }
    </style>
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center" style="height:100vh;">
        <div>
            <div class="text-center">
                <img src="<?= $router->getLogo(); ?>" alt="" style="max-width:400px;">
            </div>
            <div class="text-center mt-4">
                <a href="connexion.php" class="d-inline-block p-2 btn-ederalab" style="min-width:180px;border-radius:10px;">Se connecter</a>
            </div>
        </div>
    </div>
</body>
</html>