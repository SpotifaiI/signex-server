<?php

    namespace Signex\Methods;

    use Exception;
    use Signex\Lib\Body;
    use Signex\Lib\Response;
    use Signex\Data\User as UserModel;
    use Signex\Lib\Str;

    class Method {
        protected Response $response;
        protected readonly array $body;
        protected readonly array $form;
        protected readonly array $files;

        public function __construct(
            protected readonly array $params
        ) {
            $this->response = new Response();
            $this->body = (empty(Body::get()) && !empty(Body::form()))
                ? Body::form()
                : Body::get();
            $this->files = Body::files();
        }

        /**
         * @throws Exception
         */
        protected function validate(array $fields): void {
            $notFound = [];

            foreach ($fields as $field) {
                if (!isset($this->body[$field])) {
                    $notFound[] = $field;
                }
            }

            if (!empty($notFound)) {
                throw new Exception('Campos ['.implode(', ', $notFound).'] são obrigatórios!');
            }
        }

        /**
         * @throws Exception
         */
        protected function authenticate(string $token, int $userId): void {
            if (empty($user)) {
                throw new Exception(
                    'Usuário relacionado não identificado.'
                );
            }

            if (empty($token)) {
                throw new Exception(
                    'Token de validação não identificado.'
                );
            }

            $user = new UserModel();
            $userToken = $user->buildToken($userId);

            if (!Str::worth($userToken, $token)) {
                throw new Exception(
                    'Token de autenticação expirado ou inválido.'
                );
            }
        }
    }
