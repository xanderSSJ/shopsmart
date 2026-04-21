<?php
declare(strict_types=1);

require dirname(__DIR__) . '/app/Core/helpers.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
    $fullPath = base_path('app/' . $relativePath);

    if (is_file($fullPath)) {
        require $fullPath;
    }
});

\App\Core\Env::load(base_path('.env'));

ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
session_name('shopsmart_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['_session_initialized'])) {
    session_regenerate_id(true);
    $_SESSION['_session_initialized'] = time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['_token'] ?? '';
    if (!\App\Core\Csrf::validate(is_string($token) ? $token : null)) {
        http_response_code(419);
        \App\Core\View::render('errors/419', [
            'title' => 'Sesion expirada',
        ]);
        exit;
    }
}

$router = new \App\Core\Router();

$router->get('/', [\App\Controllers\CatalogController::class, 'home']);
$router->get('/catalogo', [\App\Controllers\CatalogController::class, 'index']);
$router->get('/producto/{id}', [\App\Controllers\CatalogController::class, 'show']);

$router->get('/login', [\App\Controllers\AuthController::class, 'showLoginForm'], ['guest']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register'], ['guest']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login'], ['guest']);
$router->post('/logout', [\App\Controllers\AuthController::class, 'logout'], ['auth']);

$router->get('/carrito', [\App\Controllers\CartController::class, 'index'], ['auth']);
$router->post('/carrito/agregar', [\App\Controllers\CartController::class, 'add'], ['auth']);
$router->post('/carrito/actualizar', [\App\Controllers\CartController::class, 'update'], ['auth']);
$router->post('/carrito/eliminar', [\App\Controllers\CartController::class, 'remove'], ['auth']);

$router->post('/checkout', [\App\Controllers\OrderController::class, 'checkout'], ['auth']);
$router->get('/mis-pedidos', [\App\Controllers\OrderController::class, 'index'], ['auth']);

$router->get('/admin/productos', [\App\Controllers\AdminProductController::class, 'index'], ['admin']);
$router->get('/admin/productos/crear', [\App\Controllers\AdminProductController::class, 'create'], ['admin']);
$router->post('/admin/productos', [\App\Controllers\AdminProductController::class, 'store'], ['admin']);
$router->get('/admin/productos/{id}/editar', [\App\Controllers\AdminProductController::class, 'edit'], ['admin']);
$router->post('/admin/productos/{id}', [\App\Controllers\AdminProductController::class, 'update'], ['admin']);
$router->post('/admin/productos/{id}/eliminar', [\App\Controllers\AdminProductController::class, 'destroy'], ['admin']);

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = is_string($uri) ? $uri : '/';

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($scriptDir !== '' && $scriptDir !== '/' && $scriptDir !== '.') {
    if (str_starts_with($uri, $scriptDir)) {
        $uri = substr($uri, strlen($scriptDir));
    }
}

if ($uri === '' || $uri === false) {
    $uri = '/';
}

$router->dispatch($method, $uri);
