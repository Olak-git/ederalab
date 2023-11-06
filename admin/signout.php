<?php

use src\Router\Router;

    require '../autoload.php';

    (new Router)->signout('admin');

    header('Location: connexion.php');
    