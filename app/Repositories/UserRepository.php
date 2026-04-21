<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT u.id_usuario, u.nombre, u.email, u.password_hash, u.id_rol, u.activo, r.nombre AS rol_nombre
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.id_rol
                WHERE u.email = :email
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT u.id_usuario, u.nombre, u.email, u.password_hash, u.id_rol, u.activo, r.nombre AS rol_nombre
                FROM usuarios u
                INNER JOIN roles r ON r.id_rol = u.id_rol
                WHERE u.id_usuario = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function createClient(string $nombre, string $email, string $passwordHash): int
    {
        $roleId = $this->getRoleIdByName('cliente');

        if ($this->driverName() === 'pgsql') {
            $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash, id_rol, saldo, activo) VALUES (:nombre, :email, :password_hash, :id_rol, 0.00, TRUE) RETURNING id_usuario');
            $stmt->execute([
                'nombre' => $nombre,
                'email' => $email,
                'password_hash' => $passwordHash,
                'id_rol' => $roleId,
            ]);

            return (int) $stmt->fetchColumn();
        }

        $stmt = $this->pdo->prepare('INSERT INTO usuarios (nombre, email, password_hash, id_rol, saldo, activo) VALUES (:nombre, :email, :password_hash, :id_rol, 0.00, 1)');
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password_hash' => $passwordHash,
            'id_rol' => $roleId,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    private function getRoleIdByName(string $name): int
    {
        $stmt = $this->pdo->prepare('SELECT id_rol FROM roles WHERE nombre = :name LIMIT 1');
        $stmt->execute(['name' => $name]);
        $roleId = $stmt->fetchColumn();

        return $roleId !== false ? (int) $roleId : 2;
    }

    private function driverName(): string
    {
        return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}

