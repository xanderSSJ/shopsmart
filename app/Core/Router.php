<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    private function add(string $method, string $path, array $handler, array $middleware): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($path),
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = $this->normalizePath($uri);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->compilePattern($route['path']);
            if (!preg_match($pattern, $uri, $matches)) {
                continue;
            }

            foreach ($route['middleware'] as $middleware) {
                if (!$this->runMiddleware($middleware)) {
                    return;
                }
            }

            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }

            [$controllerClass, $action] = $route['handler'];
            $controller = new $controllerClass();
            $controller->$action($params);
            return;
        }

        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Pagina no encontrada',
        ]);
    }

    private function runMiddleware(string $middleware): bool
    {
        if ($middleware === 'auth' && !Auth::check()) {
            Flash::set('Necesitas iniciar sesion para continuar.', 'warning');
            redirect('/login');
            return false;
        }

        if ($middleware === 'guest' && Auth::check()) {
            redirect('/catalogo');
            return false;
        }

        if ($middleware === 'admin') {
            if (!Auth::check()) {
                Flash::set('Inicia sesion para acceder al panel administrativo.', 'warning');
                redirect('/login');
                return false;
            }

            if (!Auth::isAdmin()) {
                http_response_code(403);
                View::render('errors/403', [
                    'title' => 'Acceso denegado',
                ]);
                return false;
            }
        }

        return true;
    }

    private function normalizePath(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        $normalized = '/' . ltrim($path, '/');
        if ($normalized !== '/' && str_ends_with($normalized, '/')) {
            $normalized = rtrim($normalized, '/');
        }

        return $normalized;
    }

    private function compilePattern(string $path): string
    {
        if ($path === '/') {
            return '#^/$#';
        }

        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
