<?php

    namespace Signex\Lib;

    class Response {
        private bool $isOk = false;
        private string $message = '';
        private ?array $data = null;

        public function build(): array {
            $response = [
                'ok' => $this->isOk,
                'message' => $this->message,
            ];

            if (!is_null($this->data)) {
                $response['data'] = $this->data;
            }

            return $response;
        }

        public function setOk(bool $isOk): Response {
            $this->isOk = $isOk;
            $this->data = null;

            return $this;
        }

        public function setMessage(string $message): Response {
            $this->message = $message;
            $this->data = null;

            return $this;
        }

        public function setData(array $data): Response {
            $this->data = $data;

            return $this;
        }
    }
