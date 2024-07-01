<?php

    namespace Signex\Lib;

    class Response {
        private static bool $isOk = false;
        private static string $message = '';
        private static ?array $data = null;

        public static function build(): array {
            $response = [
                'ok' => self::$isOk,
                'message' => self::$message,
            ];

            if (!is_null(self::$data)) {
                $response['data'] = self::$data;
            }

            return $response;
        }

        public static function setOk(bool $isOk): void {
            self::$isOk = $isOk;
            self::$data = null;
        }

        public static function setMessage(string $message): void {
            self::$message = $message;
            self::$data = null;
        }

        public static function setData(array $data): void {
            self::$data = $data;
        }
    }
