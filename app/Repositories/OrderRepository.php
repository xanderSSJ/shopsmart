<?php
declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class OrderRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function createOrder(int $userId, float $total, string $state = 'confirmado'): int
    {
        if ($this->driverName() === 'pgsql') {
            $stmt = $this->pdo->prepare('INSERT INTO pedidos (id_usuario, total, estado) VALUES (:id_usuario, :total, :estado) RETURNING id_pedido');
            $stmt->execute([
                'id_usuario' => $userId,
                'total' => $total,
                'estado' => $state,
            ]);

            return (int) $stmt->fetchColumn();
        }

        $stmt = $this->pdo->prepare('INSERT INTO pedidos (id_usuario, total, estado) VALUES (:id_usuario, :total, :estado)');
        $stmt->execute([
            'id_usuario' => $userId,
            'total' => $total,
            'estado' => $state,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function addOrderItem(int $orderId, int $productId, int $quantity, float $unitPrice): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO detalle_pedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario, :subtotal)');
        $stmt->execute([
            'id_pedido' => $orderId,
            'id_producto' => $productId,
            'cantidad' => $quantity,
            'precio_unitario' => $unitPrice,
            'subtotal' => $quantity * $unitPrice,
        ]);
    }

    public function getOrdersWithItemsByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT id_pedido, total, estado, fecha_pedido FROM pedidos WHERE id_usuario = :id_usuario ORDER BY fecha_pedido DESC, id_pedido DESC');
        $stmt->execute(['id_usuario' => $userId]);
        $orders = $stmt->fetchAll();

        if (count($orders) === 0) {
            return [];
        }

        $orderIds = array_map(static fn(array $order): int => (int) $order['id_pedido'], $orders);
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        $detailSql = "SELECT dp.id_pedido, dp.id_producto, dp.cantidad, dp.precio_unitario, dp.subtotal, p.nombre AS producto_nombre, p.imagen_url
                      FROM detalle_pedidos dp
                      INNER JOIN productos p ON p.id_producto = dp.id_producto
                      WHERE dp.id_pedido IN ($placeholders)
                      ORDER BY dp.id_detalle_pedido ASC";

        $detailStmt = $this->pdo->prepare($detailSql);
        foreach ($orderIds as $index => $orderId) {
            $detailStmt->bindValue($index + 1, $orderId, PDO::PARAM_INT);
        }
        $detailStmt->execute();
        $details = $detailStmt->fetchAll();

        $detailsByOrder = [];
        foreach ($details as $detail) {
            $detailsByOrder[(int) $detail['id_pedido']][] = $detail;
        }

        foreach ($orders as &$order) {
            $orderId = (int) $order['id_pedido'];
            $order['items'] = $detailsByOrder[$orderId] ?? [];
        }

        return $orders;
    }

    private function driverName(): string
    {
        return (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}

