<?php

    namespace Signex\Data;

    use Signex\Lib\Database;
    use Signex\Lib\Str;
    use Signex\Lib\Time;

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
            $statement->bindValue('user', $userId);
            $statement->bindValue('hash', Str::crypt(Time::moment()));
            $statement->bindValue('content', md5($data['content']));
            $statement->bindValue('file', $data['file']);
            $statement->execute();

            return (int) Database::getConnection()->lastInsertId();
        }

        /**
         * @return array<int, int>
         */
        public function sign(int $signId, array $emailList): array {
            $ids = [];

            foreach ($emailList as $email) {
                $statement = Database::getConnection()->prepare(
                    "INSERT IGNORE INTO signer
                    SET sign = :sign,
                        email = :email,
                        code = :code"
                );
                $statement->bindValue('sign', $signId);
                $statement->bindValue('email', $email);
                $statement->bindValue('code', Str::random(4));
                $statement->execute();
    
                $ids[] = (int) Database::getConnection()->lastInsertId();
            }

            return $ids;
        }
    }
