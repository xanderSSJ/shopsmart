<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $path = ltrim($path, '/\\');
        return $path === '' ? BASE_PATH : BASE_PATH . DIRECTORY_SEPARATOR . $path;
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = '/'): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = str_replace('\\', '/', dirname($scriptName));
        if ($baseDir === '/' || $baseDir === '\\' || $baseDir === '.') {
            $baseDir = '';
        }

        $normalizedPath = '/' . ltrim($path, '/');
        if ($normalizedPath === '//') {
            $normalizedPath = '/';
        }

        return $baseDir . $normalizedPath;
    }
}

if (!function_exists('current_path')) {
    function current_path(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $uri = is_string($uri) ? $uri : '/';

        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir !== '' && $scriptDir !== '/' && $scriptDir !== '.') {
            if (str_starts_with($uri, $scriptDir)) {
                $uri = substr($uri, strlen($scriptDir));
            }
        }

        if ($uri === '' || $uri === false) {
            return '/';
        }

        $normalized = '/' . ltrim($uri, '/');
        if ($normalized !== '/' && str_ends_with($normalized, '/')) {
            $normalized = rtrim($normalized, '/');
        }

        return $normalized;
    }
}

if (!function_exists('nav_active')) {
    function nav_active(string $path, bool $exact = false): bool
    {
        $current = current_path();
        if ($exact) {
            return $current === $path;
        }

        if ($path === '/') {
            return $current === '/';
        }

        return str_starts_with($current, $path);
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        return base_url('/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . base_url($path));
        exit;
    }
}

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return \App\Core\Csrf::token();
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?array
    {
        return \App\Core\Auth::user();
    }
}

if (!function_exists('flash')) {
    function flash(string $message, string $type = 'info'): void
    {
        \App\Core\Flash::set($message, $type);
    }
}

if (!function_exists('flash_messages')) {
    function flash_messages(): array
    {
        return \App\Core\Flash::all();
    }
}
