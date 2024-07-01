<?php

    namespace Signex\Lib;

    use Signex\Lib\Exceptions\RouterException;

    require_once SIGNEX_ROOT . '/Lib/Exceptions/RouterException.php';

    class Router {
        public static function start(): array {
            $resources = self::getResources();

            if (empty($resources['class'])) {
                throw new RouterException('Parâmetro de módulo é obrigatório!');
            }

            return $resources;
        }

        /**
         * @return array{
         *     class: string,
         *     method: string,
         *     resources: array<int, string>
         * }
         */
        private static function getResources(): array {
            $route = self::get();
            $routes = self::clean(explode('/', $route));

            return [
                'class' => $routes[0] ?? null,
                'method' => $routes[1] ?? null,
                'resources' => array_slice($routes, 2)
            ];
        }

        private static function clean(array $routes): array {
            $sortRoute = [];

            foreach ($routes as $route) {
                if (!empty($route)) {
                    $sortRoute[] = $route;
                }
            }

            return $sortRoute;
        }

        private static function get(): string {
            return $_GET['route'] ?? '';
        }
    }
