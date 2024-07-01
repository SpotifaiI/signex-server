<?php

    namespace Signex\Lib;

    class Router {
        private ?string $route;
        private Resource $resource;

        public function __construct() {
            $this->search();

            if ($this->isEmpty()) {
                throw new \Exception('Rota inválida para uso.');
            }

            $this->resource = new Resource($this->route);

            if (empty($this->resource->module)) {
                throw new \Exception('Módulo faltando na rota.');
            }

            if (empty($this->resource->method)) {
                throw new \Exception('Método faltando na rota.');
            }
        }

        public function resource(): Resource {
            return $this->resource;
        }

        private function isEmpty(): bool {
            return empty($this->route);
        }

        private function search(): Router {
            $this->route = $_GET['route'] ?? null;

            return $this;
        }
    }
