<?php

    namespace Signex;

    use Signex\Lib\Router;

    define('SIGNEX_ROOT', __DIR__);

    require_once SIGNEX_ROOT.'/Lib/Dotenv.php';
    require_once SIGNEX_ROOT.'/Lib/Database.php';
    require_once SIGNEX_ROOT.'/Lib/Router.php';

    class Signex {
        public function start(): void {
            echo Router::get();
        }
    }
