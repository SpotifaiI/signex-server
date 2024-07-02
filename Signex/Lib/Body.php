<?php

    namespace Signex\Lib;

    class Body {
        public static function get(): array {
            $content = json_decode(self::search(), true);

            if (!is_array($content)) {
                $content = [];
            }

            return $content;
        }

        private static function search(): string {
            return file_get_contents('php://input');
        }
    }
