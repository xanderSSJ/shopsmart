<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    public static function token(): string
    {
        if (!isset($_SESSION[self::TOKEN_KEY]) || !is_string($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::TOKEN_KEY];
    }

    public static function validate(?string $token): bool
    {
        if (!is_string($token) || $token === '') {
            return false;
        }

        $sessionToken = $_SESSION[self::TOKEN_KEY] ?? '';
        if (!is_string($sessionToken) || $sessionToken === '') {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }
}
