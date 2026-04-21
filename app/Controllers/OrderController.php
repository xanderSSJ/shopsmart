<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\CheckoutService;
use RuntimeException;

final class OrderController extends Controller
{
    private OrderRepository $orderRepository;
    private CheckoutService $checkoutService;

    public function __construct()
    {
        $pdo = Database::connection();
        $cartRepository = new CartRepository($pdo);

        $this->orderRepository = new OrderRepository($pdo);
        $this->checkoutService = new CheckoutService($pdo, $cartRepository, $this->orderRepository);
    }

    public function index(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        $orders = $this->orderRepository->getOrdersWithItemsByUser($userId);

        $this->render('orders/index', [
            'title' => 'Mis pedidos',
            'orders' => $orders,
        ]);
    }

    public function checkout(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        try {
            $orderId = $this->checkoutService->process($userId);
            Flash::set('Compra simulada completada. Pedido #' . $orderId . ' generado.', 'success');
            $this->redirect('/mis-pedidos');
        } catch (RuntimeException $exception) {
            Flash::set($exception->getMessage(), 'danger');
            $this->redirect('/carrito');
        }
    }
}
