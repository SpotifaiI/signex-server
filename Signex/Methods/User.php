<?php

    namespace Signex\Methods;

    use Signex\Lib\Response;

    class User {
        public function list(): Response {
            $response = new Response();

            return $response->setOk(true)->setMessage('deu bom!@');
        }
    }
