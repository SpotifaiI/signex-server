<?php

    namespace Signex\Lib;

    class Publisher {
        public function __construct(
            private readonly Response $response
        ) {}

        public function echo(): void {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->response->build());
        }
    }
