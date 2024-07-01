<?php

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');

    spl_autoload_register(function($class) {
        $path = __DIR__.'/'.str_replace('\\', '/', $class).'.php';

        if (file_exists($path)) {
            require_once $path;
        }
    });

    define('SIGNEX_ROOT', __DIR__);

    use Signex\Signex;

    (new Signex())->start();
