<?php

namespace Src\Utils;

/**
 * Class AssetLoader
 *
 * This class is responsible for loading CSS and JS files from custom paths.
 *
 * @package Src\Utils
 */
class AssetLoader {
    /**
     * Render CSS files as HTML link tags.
     *
     * @param array $files Array of CSS file paths (relative to the public directory).
     * @return string
     */
    public static function renderCss(array $files): string {
        $html = '';
        foreach ($files as $file) {
            $html .= '<link rel="stylesheet" href="' . self::getAssetPath($file) . '">' . PHP_EOL;
        }
        return $html;
    }

    /**
     * Render JS files as HTML script tags.
     *
     * @param array $files Array of JS file paths (relative to the public directory).
     * @return string
     */
    public static function renderJs(array $files): string {
        $html = '';
        foreach ($files as $file) {
            $html .= '<script src="' . self::getAssetPath($file) . '"></script>' . PHP_EOL;
        }
        return $html;
    }

    /**
     * Get the full path to the asset file.
     *
     * @param string $file The file path (relative to the public directory).
     * @return string
     */
    private static function getAssetPath(string $file): string {
        $baseUrl = '/';
        return $baseUrl . ltrim($file, '/');
    }
}