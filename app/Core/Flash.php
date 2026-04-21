<?php
declare(strict_types=1);

namespace App\Core;

final class Flash
{
    public static function set(string $message, string $type = 'info'): void
    {
        if (!isset($_SESSION['_flash']) || !is_array($_SESSION['_flash'])) {
            $_SESSION['_flash'] = [];
        }

        $_SESSION['_flash'][] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    public static function all(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return is_array($messages) ? $messages : [];
    }
}
