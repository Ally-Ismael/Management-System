<?php
declare(strict_types=1);

namespace App\Controllers;

// BASE_URL is a constant in App\Config; referenced directly in views

abstract class BaseController {
    protected function render(string $view, array $params = [], string $layout = 'main'): void {
        extract($params, EXTR_SKIP);
        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';
        $layoutFile = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        include $layoutFile;
    }
}