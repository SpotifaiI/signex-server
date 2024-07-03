<?php

    namespace Signex\Data;

    use Signex\Lib\Database;

    class Sign {
        /**
         * @param int $userId
         * @param array{content: string, file: string} $data
         * @return int
         */
        public function add(int $userId, array $data): int {
            $statement = Database::getConnection()->prepare(
                "INSERT IGNORE INTO sign
                SET user = :user,
                    hash = :hash,
                    content = :content,
                    file = :file"
            );
        }
    }
