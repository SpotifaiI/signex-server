<?php

    namespace Signex\Data;

    use Signex\Lib\Database;

    class Mail {
        /**
         * @param array{email: string, message: string} $data
         */
        public function add(array $data): int {
            $statement = Database::getConnection()->prepare(
                "INSERT IGNORE INTO mail
                SET email = :email,
                    message = :message"
            );
            $statement->bindValue('email', $data['email']);
            $statement->bindValue('message', $data['message']);
            $statement->execute();

            return (int) Database::getConnection()->lastInsertId();
        }
    }