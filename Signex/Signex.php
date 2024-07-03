<?php

    namespace Signex;

    use Signex\Lib\Publisher;
    use Signex\Lib\Response;
    use Signex\Lib\Router;

    class Signex {
        public function start(): void {
            Publisher::standup();

            $response = new Response();

            try {
                $router = new Router();

                $class = $router->resource()->module;
                $method = $router->resource()->method;
                $params = $router->resource()->params;

                $class = "\Signex\Methods\\$class";

                if (!class_exists($class)) {
                    throw new \Exception('Módulo não encontrado!');
                }

                if (!method_exists($class, $method)) {
                    throw new \Exception('Método não encontrado!');
                }

                $response = (new $class($params))->$method();
            } catch (\Exception $exception) {
                $response->setOk(false)->setMessage($exception->getMessage());
            } finally {
                (new Publisher($response))->echo();
            }
        }
    }
