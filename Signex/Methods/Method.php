<?php

    namespace Signex\Methods;

    use Signex\Lib\Body;
    use Signex\Lib\Response;

    class Method {
        protected Response $response;
        protected readonly array $body;

        public function __construct(
            protected readonly array $params
        ) {
            $this->response = new Response();
            $this->body = Body::get();
        }

        protected function validate(array $fields): void {
            $notFound = [];

            foreach ($fields as $field) {
                if (!isset($this->body[$field])) {
                    $notFound[] = $field;
                }
            }

            if (!empty($notFound)) {
                throw new \Exception('Campos ['.implode(', ', $notFound).'] são obrigatórios!');
            }
        }
    }
