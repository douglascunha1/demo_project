<?php

namespace Src\Views;

use Exception;

/**
 * This class is responsible for rendering the views
 *
 * Class View
 *
 * @package Src\Views
 */
class View {
    /**
     * Render a specific view.
     *
     * @param string $view Name of the view file to be rendered without extension.
     * @param array $args Data to be passed to the view.
     * @param string|null $layout Optional layout file to be used.
     * @throws Exception
     */
    public static function render(string $view, array $args = [], ?string $layout = null): void {
        extract($args, EXTR_SKIP);

        $basePath = __DIR__ . '/../../resources/views/';

        $viewFile = "{$basePath}{$view}.php";

        if (!is_readable($viewFile)) {
            throw new Exception("View '{$view}' not found at {$viewFile}");
        }

        if ($layout) {
            $layoutFile = "{$basePath}layouts/{$layout}.php";

            if (!is_readable($layoutFile)) {
                throw new Exception("Layout '{$layout}' not found at {$layoutFile}");
            }

            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            require $layoutFile;
        } else {
            require $viewFile;
        }
    }
}
