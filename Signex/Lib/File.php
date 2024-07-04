<?php

    namespace Signex\Lib;

    class File {
        public static function upload(array $fileMeta): Upload {
            $random = Str::random(32);
            $extension = self::getExtension($fileMeta['type']);
            $newPath = sprintf(
                "%s/%s.%s",
                self::tmpPath(),
                $random,
                $extension
            );

            move_uploaded_file($fileMeta['tmp_name'], $newPath);

            return new Upload(
                $random,
                $newPath,
                $extension
            );
        }

        private static function tmpPath(): string {
            return sys_get_temp_dir();
        }

        private static function getExtension(string $mime): string {
            $splitMime = explode('/', $mime);

            return $splitMime[count($splitMime) - 1] ?? '';
        }
    }
