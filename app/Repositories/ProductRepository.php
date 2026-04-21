<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class ProductRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getActiveProducts(string $search = '', string $category = ''): array
    {
        $sql = "SELECT p.id_producto, p.id_categoria, c.nombre AS categoria_nombre, p.nombre, p.descripcion, p.precio, p.stock, p.imagen_url, p.estado
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                WHERE p.estado = 'activo'";

        $params = [];
        if ($search !== '') {
            $sql .= ' AND (p.nombre LIKE :search OR p.descripcion LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }

        if ($category !== '') {
            $sql .= ' AND c.nombre = :category';
            $params['category'] = $category;
        }

        $sql .= ' ORDER BY p.fecha_creacion DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAllProducts(string $search = ''): array
    {
        $sql = 'SELECT p.id_producto, p.id_categoria, c.nombre AS categoria_nombre, p.nombre, p.descripcion, p.precio, p.stock, p.imagen_url, p.estado
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.id_categoria';

        $params = [];
        if ($search !== '') {
            $sql .= ' WHERE p.nombre LIKE :search OR p.descripcion LIKE :search OR c.nombre LIKE :search';
            $params['search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY p.fecha_creacion DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getActiveCategories(): array
    {
        $sql = "SELECT DISTINCT c.nombre
                FROM categorias c
                INNER JOIN productos p ON p.id_categoria = c.id_categoria
                WHERE p.estado = 'activo'
                ORDER BY c.nombre ASC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function findActiveById(int $id): ?array
    {
        $sql = "SELECT p.id_producto, p.id_categoria, c.nombre AS categoria_nombre, p.nombre, p.descripcion, p.precio, p.stock, p.imagen_url, p.estado
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                WHERE p.id_producto = :id AND p.estado = 'activo'
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT p.id_producto, p.id_categoria, c.nombre AS categoria_nombre, p.nombre, p.descripcion, p.precio, p.stock, p.imagen_url, p.estado
                FROM productos p
                INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                WHERE p.id_producto = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        return $product ?: null;
    }

    public function create(array $data): int
    {
        if ($this->driverName() === 'pgsql') {
            $sql = 'INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
                    VALUES (:id_categoria, :nombre, :descripcion, :precio, :stock, :imagen_url, :estado)
                    RETURNING id_producto';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id_categoria' => $data['id_categoria'],
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'precio' => $data['precio'],
                'stock' => $data['stock'],
                'imagen_url' => $data['imagen_url'],
                'estado' => $data['estado'],
            ]);

            return (int) $stmt->fetchColumn();
        }

        $sql = 'INSERT INTO productos (id_categoria, nombre, descripcion, precio, stock, imagen_url, estado)
                VALUES (:id_categoria, :nombre, :descripcion, :precio, :stock, :imagen_url, :estado)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_categoria' => $data['id_categoria'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'imagen_url' => $data['imagen_url'],
            'estado' => $data['estado'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE productos
                SET id_categoria = :id_categoria,
                    nombre = :nombre,
                    descripcion = :descripcion,
                    precio = :precio,
                    stock = :stock,
                    imagen_url = :imagen_url,
                    estado = :estado
                WHERE id_producto = :id';

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id_categoria' => $data['id_categoria'],
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'stock' => $data['stock'],
            'imagen_url' => $data['imagen_url'],
            'estado' => $data['estado'],
            'id' => $id,
        ]);
    }

    public function softDelete(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE productos SET estado = 'inactivo' WHERE id_producto = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function driverName(): string
    {
        return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}

