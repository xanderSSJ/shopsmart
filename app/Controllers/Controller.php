<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    protected function redirect(string $path): void
    {
        redirect($path);
    }

    protected function notFound(string $message = 'La pagina solicitada no existe.'): void
    {
        http_response_code(404);
        View::render('errors/404', [
            'title' => 'Pagina no encontrada',
            'message' => $message,
        ]);
    }
}
