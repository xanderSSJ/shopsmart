<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Repositories\ProductRepository;

final class CatalogController extends Controller
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository(Database::connection());
    }

    public function home(): void
    {
        $this->index();
    }

    public function index(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $category = trim((string) ($_GET['categoria'] ?? ''));

        $products = $this->productRepository->getActiveProducts($search, $category);
        $categories = $this->productRepository->getActiveCategories();

        $this->render('catalog/index', [
            'title' => 'Catalogo ShopSmart',
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'selectedCategory' => $category,
        ]);
    }

    public function show(array $params): void
    {
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id <= 0) {
            $this->notFound('Producto invalido.');
            return;
        }

        $product = $this->productRepository->findActiveById($id);
        if ($product === null) {
            $this->notFound('El producto solicitado no existe o esta inactivo.');
            return;
        }

        $this->render('catalog/show', [
            'title' => $product['nombre'] . ' | ShopSmart',
            'product' => $product,
        ]);
    }
}
