<?php

    namespace Signex\Methods;

    use Exception;
    use Signex\Data\Sign as SignModel;
    use Signex\Lib\File;
    use Signex\Lib\Response;

    class Sign extends Method {
        private SignModel $sign;

        public function __construct(array $params) {
            parent::__construct($params);

            $this->sign = new SignModel();
        }

        public function add(): Response {
            try {
                $userId = $this->params[0] ?? null;

                if (empty($userId)) {
                    throw new Exception(
                        'Parâmetro de ID do usuário faltando na URL.'
                    );
                }

                $this->validate(['token', 'emails']);
                $this->authenticate($this->body['token'], $userId);

                $emails = json_decode($this->body['emails'], true);
                $emails = array_map(function ($email) {
                    return $email['email'];
                }, $emails);

                $upload = File::upload($this->files['file']);
                $newFilePath = sprintf(
                    "public/%s.%s",
                    $upload->name, $upload->extension
                );

                file_put_contents(
                    $newFilePath,
                    file_get_contents($upload->path)
                );
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }
    }
