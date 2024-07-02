<?php

    namespace Signex\Lib;

    use PDO;

    class Database {
        private static ?PDO $connection;

        public static function getConnection(): PDO {
            if (empty(self::$connection)) {
                $host = Dotenv::get('DB_HOST');
                $dbName = Dotenv::get('DB_NAME');
                $dbPort = Dotenv::get('DB_PORT');

                self::$connection = new PDO(
                    sprintf(
                        "mysql:host=%s;port=%s;dbname=%s;charset=utf8",
                        $host, $dbPort, $dbName
                    ),
                    Dotenv::get('DB_USER'),
                    Dotenv::get('DB_PASS')
                );
            }

            return self::$connection;
        }

        public static function close(): Database {
            self::$connection = null;

            return new self;
        }
    }
