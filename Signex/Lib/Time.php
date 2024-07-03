<?php

    namespace Signex\Lib;

    class Time {
        public static function now(): string {
            return date('Y-m-d H:i:s');
        }

        public static function moment(): string {
            return microtime();
        }
    }