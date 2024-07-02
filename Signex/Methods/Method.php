<?php

    namespace Signex\Methods;

    use Signex\Lib\Response;

    class Method {
        protected Response $response;

        public function __construct() {
            $this->response = new Response();
        }
    }
