<?php
declare(strict_types=1);

use App\Core\Env;

$connection = Env::get('DB_CONNECTION', 'pgsql');
$host = Env::get('DB_HOST', 'localhost');
$port = Env::get('DB_PORT', '5432');
$database = Env::get('DB_DATABASE', 'shopsmart_db');
$username = Env::get('DB_USERNAME', 'shopsmart_user');
$password = Env::get('DB_PASSWORD', 'ShopSmart_2026!');
$charset = Env::get('DB_CHARSET', 'utf8');
$sslMode = Env::get('DB_SSLMODE', 'prefer');

$databaseUrl = Env::get('DATABASE_URL');
if (is_string($databaseUrl) && $databaseUrl !== '') {
    $parsed = parse_url($databaseUrl);
    if ($parsed !== false) {
        $scheme = strtolower((string) ($parsed['scheme'] ?? ''));
        if ($scheme === 'postgresql' || $scheme === 'postgres') {
            $connection = 'pgsql';
        } elseif ($scheme === 'mysql' || $scheme === 'mariadb') {
            $connection = 'mysql';
        }

        $host = (string) ($parsed['host'] ?? $host);
        $port = (string) ($parsed['port'] ?? $port);
        $username = isset($parsed['user']) ? urldecode((string) $parsed['user']) : $username;
        $password = isset($parsed['pass']) ? urldecode((string) $parsed['pass']) : $password;
        if (isset($parsed['path'])) {
            $database = ltrim((string) $parsed['path'], '/');
        }

        if (isset($parsed['query'])) {
            parse_str((string) $parsed['query'], $query);
            if (isset($query['sslmode']) && is_string($query['sslmode']) && $query['sslmode'] !== '') {
                $sslMode = $query['sslmode'];
            }
        }
    }
}

return [
    'connection' => $connection,
    'host' => $host,
    'port' => $port,
    'database' => $database,
    'username' => $username,
    'password' => $password,
    'charset' => $charset,
    'sslmode' => $sslMode,
];
