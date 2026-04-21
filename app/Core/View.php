<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'main'): void
    {
        $viewFile = base_path('app/Views/' . $view . '.php');
        if (!is_file($viewFile)) {
            throw new RuntimeException('Vista no encontrada: ' . $view);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = (string) ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = base_path('app/Views/layouts/' . $layout . '.php');
        if (!is_file($layoutFile)) {
            echo $content;
            return;
        }

        require $layoutFile;
    }
}
