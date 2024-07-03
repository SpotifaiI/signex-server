<?php

    namespace Signex\Lib;

    class Publisher {
        public function __construct(
            private readonly Response $response
        ) {}

        public static function standup(): void {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

            if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
                http_response_code(204);
                exit();
            }
        }

        public function echo(): void {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->response->build());
        }
    }
