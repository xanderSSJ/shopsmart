<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CartRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getActiveCartByUser(int $userId): ?array
    {
        $sql = "SELECT id_carrito, id_usuario, estado
                FROM carritos
                WHERE id_usuario = :id_usuario AND estado = 'activo'
                ORDER BY id_carrito DESC
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_usuario' => $userId]);
        $cart = $stmt->fetch();

        return $cart ?: null;
    }

    public function createActiveCart(int $userId): int
    {
        if ($this->driverName() === 'pgsql') {
            $stmt = $this->pdo->prepare("INSERT INTO carritos (id_usuario, estado) VALUES (:id_usuario, 'activo') RETURNING id_carrito");
            $stmt->execute(['id_usuario' => $userId]);
            return (int) $stmt->fetchColumn();
        }

        $stmt = $this->pdo->prepare("INSERT INTO carritos (id_usuario, estado) VALUES (:id_usuario, 'activo')");
        $stmt->execute(['id_usuario' => $userId]);

        return (int) $this->pdo->lastInsertId();
    }

    public function getOrCreateActiveCart(int $userId): array
    {
        $cart = $this->getActiveCartByUser($userId);
        if ($cart !== null) {
            return $cart;
        }

        $cartId = $this->createActiveCart($userId);
        return [
            'id_carrito' => $cartId,
            'id_usuario' => $userId,
            'estado' => 'activo',
        ];
    }

    public function getItems(int $cartId): array
    {
        $sql = 'SELECT dc.id_detalle_carrito, dc.id_carrito, dc.id_producto, dc.cantidad, dc.precio_unitario, dc.subtotal,
                       p.nombre, p.imagen_url, p.stock, p.estado
                FROM detalle_carrito dc
                INNER JOIN productos p ON p.id_producto = dc.id_producto
                WHERE dc.id_carrito = :id_carrito
                ORDER BY dc.id_detalle_carrito DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_carrito' => $cartId]);

        return $stmt->fetchAll();
    }

    public function getTotal(int $cartId): float
    {
        $stmt = $this->pdo->prepare('SELECT COALESCE(SUM(subtotal), 0) AS total FROM detalle_carrito WHERE id_carrito = :id_carrito');
        $stmt->execute(['id_carrito' => $cartId]);

        return (float) ($stmt->fetchColumn() ?: 0.0);
    }

    public function findItemById(int $detailId, int $cartId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_detalle_carrito, id_carrito, id_producto, cantidad, precio_unitario FROM detalle_carrito WHERE id_detalle_carrito = :detail AND id_carrito = :cart LIMIT 1');
        $stmt->execute(['detail' => $detailId, 'cart' => $cartId]);
        $item = $stmt->fetch();

        return $item ?: null;
    }

    public function findItemByProduct(int $cartId, int $productId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id_detalle_carrito, id_carrito, id_producto, cantidad, precio_unitario FROM detalle_carrito WHERE id_carrito = :cart AND id_producto = :product LIMIT 1');
        $stmt->execute(['cart' => $cartId, 'product' => $productId]);
        $item = $stmt->fetch();

        return $item ?: null;
    }

    public function addOrIncrementItem(int $cartId, int $productId, int $quantity, float $price): void
    {
        $existing = $this->findItemByProduct($cartId, $productId);

        if ($existing !== null) {
            $newQuantity = (int) $existing['cantidad'] + $quantity;
            $subtotal = $newQuantity * $price;

            $stmt = $this->pdo->prepare('UPDATE detalle_carrito SET cantidad = :cantidad, precio_unitario = :precio_unitario, subtotal = :subtotal WHERE id_detalle_carrito = :id_detalle_carrito');
            $stmt->execute([
                'cantidad' => $newQuantity,
                'precio_unitario' => $price,
                'subtotal' => $subtotal,
                'id_detalle_carrito' => $existing['id_detalle_carrito'],
            ]);
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO detalle_carrito (id_carrito, id_producto, cantidad, precio_unitario, subtotal) VALUES (:id_carrito, :id_producto, :cantidad, :precio_unitario, :subtotal)');
        $stmt->execute([
            'id_carrito' => $cartId,
            'id_producto' => $productId,
            'cantidad' => $quantity,
            'precio_unitario' => $price,
            'subtotal' => $quantity * $price,
        ]);
    }

    public function updateQuantity(int $detailId, int $quantity, float $price): void
    {
        $stmt = $this->pdo->prepare('UPDATE detalle_carrito SET cantidad = :cantidad, precio_unitario = :precio_unitario, subtotal = :subtotal WHERE id_detalle_carrito = :id_detalle_carrito');
        $stmt->execute([
            'cantidad' => $quantity,
            'precio_unitario' => $price,
            'subtotal' => $quantity * $price,
            'id_detalle_carrito' => $detailId,
        ]);
    }

    public function removeItem(int $detailId, int $cartId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM detalle_carrito WHERE id_detalle_carrito = :detail AND id_carrito = :cart');
        $stmt->execute([
            'detail' => $detailId,
            'cart' => $cartId,
        ]);
    }

    public function closeCart(int $cartId): void
    {
        $stmt = $this->pdo->prepare("UPDATE carritos SET estado = 'cerrado' WHERE id_carrito = :id");
        $stmt->execute(['id' => $cartId]);
    }

    private function driverName(): string
    {
        return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}

