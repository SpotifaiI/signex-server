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

        public function delete(int $signId): void {
            $statement = Database::getConnection()->prepare(
                "DELETE FROM signer
                WHERE sign = :sign"
            );
            $statement->bindValue('sign', $signId);
            $statement->execute();

            $statement = Database::getConnection()->prepare(
                "DELETE FROM sign
                WHERE id = :sign"
            );
            $statement->bindValue('sign', $signId);
            $statement->execute();
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

        public function getFileForSign(
            int $signerId,
            string $hash
        ): array {
            $signs = [];

            $statement = Database::getConnection()->prepare(
                "SELECT sign.file, (
                    CASE
                        WHEN signer.hash IS NOT NULL THEN '1'
                    END
                ) is_signed
                FROM signer
                LEFT JOIN sign ON sign.id = signer.sign
                WHERE signer.id = :signer
                    AND sign.hash = :hash"
            );
            $statement->bindValue('signer', $signerId);
            $statement->bindValue('hash', $hash);
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $signs[] = $row;
            }

            return $signs;
        }

        public function verify(
            int $signerId,
            string $code
        ): array {
            $signs = [];

            $statement = Database::getConnection()->prepare(
                "SELECT signer.id
                FROM signer
                WHERE signer.id = :signer
                    AND signer.code = :code"
            );
            $statement->bindValue('signer', $signerId);
            $statement->bindValue('code', $code);
            $statement->execute();

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $signs[] = $row;
            }

            return $signs;
        }

        public function finish(int $signerId): bool {
            $statement = Database::getConnection()->prepare(
                "UPDATE signer
                SET hash = :hash, 
                    signed_at = :signed_at
                WHERE id = :id"
            );
            $statement->bindValue('hash', Str::crypt(sprintf(
                "%d-%s",
                $signerId, Time::moment()
            )));
            $statement->bindValue('signed_at', Time::now());
            $statement->bindValue('id', $signerId);

            return $statement->execute();
        }
    }
