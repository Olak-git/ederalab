<?php

function autoload($class)
{
    require_once __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
} 
spl_autoload_register('autoload');