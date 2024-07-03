<?php

    namespace Signex\Methods;

    use Signex\Data\Sign as SignModel;
    use Signex\Lib\Response;

    class Sign extends Method {
        private SignModel $sign;

        public function __construct(array $params) {
            parent::__construct($params);

            $this->sign = new SignModel();
        }

        public function add(): Response {
            print_r($this->form);
            print_r($_FILES);

            return $this->response;
        }
    }
