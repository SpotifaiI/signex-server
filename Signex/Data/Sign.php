<?php

    namespace Signex\Data;

    use PDO;
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

        /**
         * @param array<int, int> $signersIds
         * @return array<int, array{
         *  signer: int,
         *  email: string,
         *  hash: string,
         *  code: string
         * }>
         */
        public function getToSign(array $signersIds): array {
            $signers = [];

            $signersIdsIn = implode(',', $signersIds);

            $statement = Database::getConnection()->prepare(
                "SELECT signer.id signer, signer.email, sign.hash,
                        signer.code
                FROM signer
                LEFT JOIN sign on sign.id = signer.sign
                WHERE signer.id IN ({$signersIdsIn})"
            );
            $statement->execute();
            
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $signers[] = $row;
            }

            return $signers;
        }
        
        public function getByUser(int $userId): array {
            if (empty($userId)) {
                return [];
            }

            $signs = [];

            $statement = Database::getConnection()->prepare(
                "SELECT hash, file, id
                FROM sign
                WHERE user = :user"
            );
            $statement->bindValue('user', $userId);
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $signs[] = $row;
            }

            foreach ($signs as &$sign) {
                $sign['signers'] = $this->listSigners($sign['id']);
            }

            return $signs;
        }

        private function listSigners(int $signId): array {
            if (empty($signId)) {
                return [];
            }

            $signers = [];

            $statement = Database::getConnection()->prepare(
                "SELECT email, (
                    CASE
                        WHEN hash IS NOT NULL THEN '1'
                    END
                ) is_signed
                FROM signer
                WHERE sign = :sign"
            );
            $statement->bindValue('sign', $signId);
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $signers[] = $row;
            }

            return $signers;
        }
    }
