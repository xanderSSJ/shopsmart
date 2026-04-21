<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id' => (int) $user['id_usuario'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'rol_id' => (int) $user['id_rol'],
            'rol_nombre' => $user['rol_nombre'],
        ];

        session_regenerate_id(true);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function id(): ?int
    {
        return self::check() ? (int) $_SESSION['user']['id'] : null;
    }

    public static function isAdmin(): bool
    {
        return self::check() && ($_SESSION['user']['rol_nombre'] ?? '') === 'admin';
    }

    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                (bool) ($params['secure'] ?? false),
                (bool) ($params['httponly'] ?? true)
            );
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);
    }
}
