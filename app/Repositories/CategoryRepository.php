<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC');
        return $stmt->fetchAll();
    }

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT id_categoria FROM categorias WHERE id_categoria = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() !== false;
    }
}

