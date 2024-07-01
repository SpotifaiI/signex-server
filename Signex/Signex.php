<?php

    namespace Signex;

    use Signex\Lib\Response;
    use Signex\Lib\Router;

    class Signex {
        public function start(): void {
            try {
                $router = new Router();

                print_r($router->resource());
            } catch (\Exception $exception) {
                Response::setOk(false);
                Response::setMessage($exception->getMessage());
            } finally {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(Response::build());
            }
        }
    }
