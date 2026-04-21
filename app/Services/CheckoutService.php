<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use PDO;
use RuntimeException;
use Throwable;

final class CheckoutService
{
    public function __construct(
        private PDO $pdo,
        private CartRepository $cartRepository,
        private OrderRepository $orderRepository
    ) {
    }

    public function process(int $userId): int
    {
        $cart = $this->cartRepository->getOrCreateActiveCart($userId);
        $cartId = (int) $cart['id_carrito'];

        $items = $this->cartRepository->getItems($cartId);
        if (count($items) === 0) {
            throw new RuntimeException('Tu carrito esta vacio.');
        }

        $validatedItems = [];
        $total = 0.0;

        $this->pdo->beginTransaction();

        try {
            foreach ($items as $item) {
                $productStmt = $this->pdo->prepare('SELECT id_producto, nombre, stock, estado FROM productos WHERE id_producto = :id_producto FOR UPDATE');
                $productStmt->execute(['id_producto' => $item['id_producto']]);
                $product = $productStmt->fetch();

                if ($product === false || (string) $product['estado'] !== 'activo') {
                    throw new RuntimeException('Uno de los productos ya no esta disponible.');
                }

                $quantity = (int) $item['cantidad'];
                $stock = (int) $product['stock'];
                if ($stock < $quantity) {
                    throw new RuntimeException('Stock insuficiente para ' . $product['nombre'] . '.');
                }

                $unitPrice = (float) $item['precio_unitario'];
                $lineTotal = $unitPrice * $quantity;
                $total += $lineTotal;

                $validatedItems[] = [
                    'product_id' => (int) $item['id_producto'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                ];
            }

            $orderId = $this->orderRepository->createOrder($userId, $total, 'confirmado');

            foreach ($validatedItems as $validatedItem) {
                $this->orderRepository->addOrderItem(
                    $orderId,
                    $validatedItem['product_id'],
                    $validatedItem['quantity'],
                    $validatedItem['unit_price']
                );

                $stockUpdate = $this->pdo->prepare('UPDATE productos SET stock = stock - :qty WHERE id_producto = :id_producto');
                $stockUpdate->execute([
                    'qty' => $validatedItem['quantity'],
                    'id_producto' => $validatedItem['product_id'],
                ]);
            }

            $this->cartRepository->closeCart($cartId);
            $this->cartRepository->createActiveCart($userId);

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            if ($throwable instanceof RuntimeException) {
                throw $throwable;
            }

            throw new RuntimeException('No se pudo completar la compra simulada.');
        }
    }
}

