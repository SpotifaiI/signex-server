<?php

    namespace Signex\Methods;

    use Exception;
    use Signex\Data\User as UserModel;
    use Signex\Lib\Response;
    use Signex\Lib\Str;

    class User extends Method {
        private UserModel $user;

        public function __construct(array $params) {
            parent::__construct($params);

            $this->user = new UserModel();
        }

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

        public function login(): Response {
            try {
                $this->validate(['email', 'password']);

                $userExists = $this->user->getByEmail($this->body['email']);

                $validUser = [];
                foreach ($userExists as $user) {
                    if (Str::worth(
                        $this->body['password'], $user['password']
                    )) {
                        $validUser = $user;

                        break;
                    }
                }

                if (empty($validUser)) {
                    throw new Exception('Usuário ou senha inválidos!');
                }

                $token = Str::crypt(
                    $this->user->buildToken($validUser['id'])
                );

                $this->response
                    ->setOk(true)
                    ->setMessage('Usuário logado com sucesso!')
                    ->setData([
                        'token' => $token,
                        'user' => $validUser['id'],
                        'email' => $validUser['email'],
                        'name' => $validUser['name']
                    ]);
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }
    }
