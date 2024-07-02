<?php

    namespace Signex\Lib;

    class Resource {
        public ?string $module;
        public ?string $method;
        public ?array $params;

        private array $routes;

        public function __construct(
            private readonly string $route
        ) {
            $this->init()->clean()->build();
        }

        private function build(): void {
            $this->module = $this->routes[0] ?? null;
            $this->method = $this->routes[1] ?? null;
            $this->params = array_slice($this->routes, 2);
        }

        private function init(): Resource {
            $this->routes = explode('/', $this->route);

            return $this;
        }

        private function clean(): Resource {
            $sortRoute = [];

            foreach ($this->routes as $indexRoute => $route) {
                if (!empty($route)) {
                    $route = trim($route);

                    if ($indexRoute === 0) {
                        $route = ucfirst($route);
                    }

                    $sortRoute[] = $route;
                }
            }

            $this->routes = $sortRoute;

            return $this;
        }
    }
