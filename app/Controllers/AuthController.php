<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Flash;
use App\Repositories\CartRepository;
use App\Repositories\UserRepository;

final class AuthController extends Controller
{
    private UserRepository $userRepository;
    private CartRepository $cartRepository;

    public function __construct()
    {
        $pdo = Database::connection();
        $this->userRepository = new UserRepository($pdo);
        $this->cartRepository = new CartRepository($pdo);
    }

    public function showLoginForm(): void
    {
        $this->render('auth/login', [
            'title' => 'Acceso a ShopSmart',
        ]);
    }

    public function register(): void
    {
        $name = trim((string) ($_POST['nombre'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if (strlen($name) < 2) {
            Flash::set('El nombre debe tener al menos 2 caracteres.', 'danger');
            $this->redirect('/login');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('Ingresa un correo electronico valido.', 'danger');
            $this->redirect('/login');
            return;
        }

        if (strlen($password) < 8) {
            Flash::set('La contrasena debe tener al menos 8 caracteres.', 'danger');
            $this->redirect('/login');
            return;
        }

        if ($this->userRepository->findByEmail($email) !== null) {
            Flash::set('Ya existe una cuenta con ese correo.', 'danger');
            $this->redirect('/login');
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $userId = $this->userRepository->createClient($name, $email, $passwordHash);
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            Flash::set('No se pudo completar el registro.', 'danger');
            $this->redirect('/login');
            return;
        }

        Auth::login($user);
        $this->cartRepository->getOrCreateActiveCart((int) $user['id_usuario']);

        Flash::set('Tu cuenta fue creada correctamente. Bienvenido a ShopSmart!', 'success');
        $this->redirect('/catalogo');
    }

    public function login(): void
    {
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            Flash::set('Correo o contrasena invalidos.', 'danger');
            $this->redirect('/login');
            return;
        }

        $user = $this->userRepository->findByEmail($email);
        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            Flash::set('Correo o contrasena incorrectos.', 'danger');
            $this->redirect('/login');
            return;
        }

        $activeRaw = $user['activo'] ?? 0;
        $activeBool = filter_var($activeRaw, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
        $isActive = $activeBool !== null ? $activeBool : ((int) $activeRaw === 1);

        if (!$isActive) {
            Flash::set('Tu cuenta esta inactiva. Contacta al administrador.', 'danger');
            $this->redirect('/login');
            return;
        }

        Auth::login($user);
        $this->cartRepository->getOrCreateActiveCart((int) $user['id_usuario']);

        Flash::set('Sesion iniciada correctamente.', 'success');
        if (($user['rol_nombre'] ?? '') === 'admin') {
            $this->redirect('/admin/productos');
            return;
        }

        $this->redirect('/catalogo');
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::set('Sesion cerrada correctamente.', 'info');
        $this->redirect('/login');
    }
}
