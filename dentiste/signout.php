<?php

use src\Router\Router;

    require '../autoload.php';

    (new Router)->signout('dentiste');

    header('Location: connexion.php');
    