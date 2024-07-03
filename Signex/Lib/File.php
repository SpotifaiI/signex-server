<?php

    namespace Signex\Lib;

    class File {
        public static function upload(array $fileMeta) {
            $newPath = sprintf(
                "%s/%s.%s",
                self::tmpPath(),
                $fileMeta['name'],
                self::getExtension($fileMeta['type'])
            );

            move_uploaded_file($fileMeta['tmp_name'], $newPath);

            return $newPath;
        }

        private static function tmpPath(): string {
            return sys_get_temp_dir();
        }

        private static function getExtension(string $mime): string {
            $splitMime = explode('/', $mime);

            return $splitMime[count($splitMime) - 1] ?? '';
        }
    }
