<?php

    ini_set('error_reporting', E_ALL);

    require_once __DIR__.'/Signex/Signex.php';

    use Signex\Signex;

    (new Signex())->start();
