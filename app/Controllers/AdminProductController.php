<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;
use App\Core\Flash;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;

final class AdminProductController extends Controller
{
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct()
    {
        $pdo = Database::connection();
        $this->productRepository = new ProductRepository($pdo);
        $this->categoryRepository = new CategoryRepository($pdo);
    }

    public function index(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $products = $this->productRepository->getAllProducts($search);

        $this->render('admin/products/index', [
            'title' => 'Administracion de productos',
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create(): void
    {
        $categories = $this->categoryRepository->all();

        $this->render('admin/products/form', [
            'title' => 'Nuevo producto',
            'categories' => $categories,
            'product' => [
                'id_categoria' => '',
                'nombre' => '',
                'descripcion' => '',
                'precio' => '0.00',
                'stock' => '0',
                'imagen_url' => '',
                'estado' => 'activo',
            ],
            'action' => '/admin/productos',
            'submitLabel' => 'Crear producto',
        ]);
    }

    public function store(): void
    {
        [$data, $errors] = $this->validateInput($_POST);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                Flash::set($error, 'danger');
            }
            $this->redirect('/admin/productos/crear');
            return;
        }

        $this->productRepository->create($data);
        Flash::set('Producto creado correctamente.', 'success');
        $this->redirect('/admin/productos');
    }

    public function edit(array $params): void
    {
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id <= 0) {
            $this->notFound('Producto invalido.');
            return;
        }

        $product = $this->productRepository->findById($id);
        if ($product === null) {
            $this->notFound('No se encontro el producto.');
            return;
        }

        $categories = $this->categoryRepository->all();

        $this->render('admin/products/form', [
            'title' => 'Editar producto',
            'categories' => $categories,
            'product' => $product,
            'action' => '/admin/productos/' . $id,
            'submitLabel' => 'Guardar cambios',
        ]);
    }

    public function update(array $params): void
    {
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id <= 0) {
            $this->notFound('Producto invalido.');
            return;
        }

        if ($this->productRepository->findById($id) === null) {
            $this->notFound('No se encontro el producto.');
            return;
        }

        [$data, $errors] = $this->validateInput($_POST);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                Flash::set($error, 'danger');
            }
            $this->redirect('/admin/productos/' . $id . '/editar');
            return;
        }

        $this->productRepository->update($id, $data);
        Flash::set('Producto actualizado correctamente.', 'success');
        $this->redirect('/admin/productos');
    }

    public function destroy(array $params): void
    {
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id <= 0) {
            Flash::set('Producto invalido.', 'danger');
            $this->redirect('/admin/productos');
            return;
        }

        if ($this->productRepository->findById($id) === null) {
            Flash::set('No se encontro el producto.', 'danger');
            $this->redirect('/admin/productos');
            return;
        }

        $this->productRepository->softDelete($id);
        Flash::set('Producto desactivado correctamente.', 'info');
        $this->redirect('/admin/productos');
    }

    private function validateInput(array $input): array
    {
        $errors = [];

        $categoryId = (int) ($input['id_categoria'] ?? 0);
        $name = trim((string) ($input['nombre'] ?? ''));
        $description = trim((string) ($input['descripcion'] ?? ''));
        $priceRaw = str_replace(',', '.', trim((string) ($input['precio'] ?? '')));
        $stockRaw = trim((string) ($input['stock'] ?? '0'));
        $imageUrl = trim((string) ($input['imagen_url'] ?? ''));
        $state = trim((string) ($input['estado'] ?? 'activo'));

        if (!$this->categoryRepository->exists($categoryId)) {
            $errors[] = 'Selecciona una categoria valida.';
        }

        if ($name === '' || strlen($name) < 3) {
            $errors[] = 'El nombre del producto debe tener al menos 3 caracteres.';
        }

        if (!is_numeric($priceRaw) || (float) $priceRaw < 0.01) {
            $errors[] = 'El precio debe ser numerico y mayor a 0.';
        }

        if (!ctype_digit($stockRaw) || (int) $stockRaw < 0) {
            $errors[] = 'El stock debe ser un numero entero mayor o igual a 0.';
        }

        if ($imageUrl !== '' && filter_var($imageUrl, FILTER_VALIDATE_URL) === false) {
            $errors[] = 'La imagen debe ser una URL valida.';
        }

        if (!in_array($state, ['activo', 'inactivo'], true)) {
            $errors[] = 'El estado del producto es invalido.';
        }

        return [[
            'id_categoria' => $categoryId,
            'nombre' => $name,
            'descripcion' => $description,
            'precio' => number_format((float) $priceRaw, 2, '.', ''),
            'stock' => (int) $stockRaw,
            'imagen_url' => $imageUrl,
            'estado' => $state,
        ], $errors];
    }
}
