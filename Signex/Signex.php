<?php

    namespace Signex;

    use Signex\Lib\Database;
    use PDO;

    define('SIGNEX_ROOT', __DIR__);

    require_once SIGNEX_ROOT.'/Lib/Dotenv.php';
    require_once SIGNEX_ROOT.'/Lib/Database.php';

    class Signex {
        public function start(): void {}
    }
