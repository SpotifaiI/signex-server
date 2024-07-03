<?php

    namespace Signex\Lib;

    class Str {
        public static function crypt(string $text): string {
            return password_hash($text, PASSWORD_BCRYPT, [
                'cost' => 12,
            ]);
        }

        public static function worth(string $text, string $hash): bool {
            return password_verify($text, $hash);
        }
    }
