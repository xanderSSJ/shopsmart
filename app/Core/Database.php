<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = require base_path('config/database.php');

        $driver = (string) ($config['connection'] ?? 'pgsql');
        $dsn = self::buildDsn($driver, $config);

        try {
            self::$connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo conectar a la base de datos: ' . $exception->getMessage());
        }

        return self::$connection;
    }

    private static function buildDsn(string $driver, array $config): string
    {
        if ($driver === 'mysql') {
            return sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );
        }

        $sslMode = (string) ($config['sslmode'] ?? 'prefer');
        return sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $sslMode
        );
    }
}
