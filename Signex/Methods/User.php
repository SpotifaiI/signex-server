<?php

    namespace Signex\Methods;

    use Signex\Lib\Database;
    use Signex\Lib\Dotenv;
    use Signex\Lib\Response;

    class User extends Method {
        /**
         * @throws \Exception
         */
        public function create(): Response {
            $this->validate(['name', 'email', 'password']);

            $statement = Database::getConnection()->prepare(
                "INSERT IGNORE INTO user
                SET name=:name, 
                    email=:email, 
                    password=:password"
            );
            $statement->bindValue('name', $this->body['name']);
            $statement->bindValue('email', $this->body['email']);
            $statement->bindValue('password', crypt(
                $this->body['password'],
                Dotenv::get('CRYPT_SALT')
            ));
            $statement->execute();

            $lastId = Database::getConnection()->lastInsertId();

            if (!empty($lastId)) {
                $this->response
                    ->setOk(true)
                    ->setMessage('Usuário criado com sucesso!')
                    ->setData([
                        'user' => $lastId
                    ]);
            } else {
                $this->response
                    ->setOk(false)
                    ->setMessage('Ocorreu um erro e não foi possível criar o usuário.');
            }

            return $this->response;
        }
    }
