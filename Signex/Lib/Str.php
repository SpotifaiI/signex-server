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

        public static function random(
            int $lenght,
            bool $letters = true,
            bool $ucLetters = true,
            bool $numbers = true,
            string|null $customChars = null
        ) : string|false {
            $rtn = false;
            $lenght = ($lenght > 0 ? $lenght : 16);

            $char = ($letters ? 'abcdefghijklmnopqrstuvwxyz' : '');
            $char .= ($ucLetters ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '');
            $char .= ($numbers ? '0123456789' : '');
            $char .= (!is_null($customChars) && !empty(trim($customChars)) ? trim($customChars) : '');
            $char = str_split($char);

            if(!empty($char)){
                shuffle($char);
                $totalChars = count($char) - 1;
                for($i = 0; $i < $lenght; $i++){
                    $rtn .= $char[mt_rand(0,$totalChars)];
                }
            }

            return $rtn;

        }
    }
