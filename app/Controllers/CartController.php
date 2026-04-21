<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

final class CartController extends Controller
{
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    public function __construct()
    {
        $pdo = Database::connection();
        $this->cartRepository = new CartRepository($pdo);
        $this->productRepository = new ProductRepository($pdo);
    }

    public function index(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        $cart = $this->cartRepository->getOrCreateActiveCart($userId);
        $items = $this->cartRepository->getItems((int) $cart['id_carrito']);
        $total = $this->cartRepository->getTotal((int) $cart['id_carrito']);

        $this->render('cart/index', [
            'title' => 'Mi carrito',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        $productId = (int) ($_POST['producto_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['cantidad'] ?? 1));

        $product = $this->productRepository->findActiveById($productId);
        if ($product === null) {
            Flash::set('El producto no esta disponible.', 'danger');
            $this->redirect('/catalogo');
            return;
        }

        $cart = $this->cartRepository->getOrCreateActiveCart($userId);
        $cartId = (int) $cart['id_carrito'];

        $existing = $this->cartRepository->findItemByProduct($cartId, $productId);
        $currentQuantity = $existing !== null ? (int) $existing['cantidad'] : 0;
        $newQuantity = $currentQuantity + $quantity;

        if ($newQuantity > (int) $product['stock']) {
            Flash::set('No hay suficiente stock para la cantidad solicitada.', 'warning');
            $this->redirect('/carrito');
            return;
        }

        $this->cartRepository->addOrIncrementItem($cartId, $productId, $quantity, (float) $product['precio']);
        Flash::set('Producto agregado al carrito.', 'success');
        $this->redirect('/carrito');
    }

    public function update(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        $detailId = (int) ($_POST['detalle_id'] ?? 0);
        $quantity = (int) ($_POST['cantidad'] ?? 1);

        if ($detailId <= 0 || $quantity <= 0) {
            Flash::set('Datos invalidos para actualizar el carrito.', 'danger');
            $this->redirect('/carrito');
            return;
        }

        $cart = $this->cartRepository->getOrCreateActiveCart($userId);
        $cartId = (int) $cart['id_carrito'];

        $item = $this->cartRepository->findItemById($detailId, $cartId);
        if ($item === null) {
            Flash::set('El producto no pertenece a tu carrito.', 'danger');
            $this->redirect('/carrito');
            return;
        }

        $product = $this->productRepository->findActiveById((int) $item['id_producto']);
        if ($product === null) {
            Flash::set('El producto ya no esta disponible.', 'danger');
            $this->redirect('/carrito');
            return;
        }

        if ($quantity > (int) $product['stock']) {
            Flash::set('No hay stock suficiente para esa cantidad.', 'warning');
            $this->redirect('/carrito');
            return;
        }

        $this->cartRepository->updateQuantity($detailId, $quantity, (float) $item['precio_unitario']);
        Flash::set('Cantidad actualizada.', 'success');
        $this->redirect('/carrito');
    }

    public function remove(): void
    {
        $userId = Auth::id();
        if ($userId === null) {
            $this->redirect('/login');
            return;
        }

        $detailId = (int) ($_POST['detalle_id'] ?? 0);
        if ($detailId <= 0) {
            Flash::set('No se pudo eliminar el producto.', 'danger');
            $this->redirect('/carrito');
            return;
        }

        $cart = $this->cartRepository->getOrCreateActiveCart($userId);
        $this->cartRepository->removeItem($detailId, (int) $cart['id_carrito']);

        Flash::set('Producto eliminado del carrito.', 'info');
        $this->redirect('/carrito');
    }
}
