<?php

    namespace Signex\Methods;

    use Exception;
    use Signex\Data\User as UserModel;
    use Signex\Lib\Response;

    class User extends Method {
        private UserModel $user;

        public function __construct(array $params) {
            parent::__construct($params);

            $this->user = new UserModel();
        }

        /**
         * @throws Exception
         */
        public function create(): Response {
            try {
                $this->validate(['name', 'email', 'password']);

                $userExists = $this->user->getByEmail($this->body['email']);

                if (!empty($userExists)) {
                    throw new Exception(sprintf(
                        "Já existe um usuário cadastrado com o e-mail %s",
                        $this->body['email']
                    ));
                }

                $userId = $this->user->add($this->body);

                if (empty($userId)) {
                    throw new Exception(
                        'Ocorreu um erro e não foi possível criar o usuário.'
                    );
                }

                $this->response
                    ->setOk(true)
                    ->setMessage('Usuário criado com sucesso!')
                    ->setData([
                        'user' => $userId
                    ]);
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }
    }
