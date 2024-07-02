<?php

    namespace Signex\Methods;

    use Signex\Lib\Response;

    class User extends Method {
        public function list(): Response {
            return $this->response;
        }
    }
