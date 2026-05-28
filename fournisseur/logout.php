<?php

use src\Router\Router;

    require '../autoload.php';

    (new Router)->signout('transporteur');

    header('Location: connexion.php');
    