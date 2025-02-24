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
     * Render font files as HTML link tags.
     *
     * @param array $files Array of font file paths (relative to the public directory).
     * @return string
     */
    public static function renderFont(array $files): string {
        $html = '';
        foreach ($files as $file) {
            $html .= '<link rel="stylesheet" href="' . self::getAssetPath($file) . '">' . PHP_EOL;
        }
        return $html;
    }

    /**
     * Render Bootstrap CSS.
     *
     * @return string
     */
    public static function renderBootstrapCss(): string {
        return self::renderCss([
            '/css/bootstrap/bootstrap.min.css'
        ]);
    }

    /**
     * Render Bootstrap JS.
     *
     * @return string
     */
    public static function renderBootstrapJs(): string {
        return self::renderJs([
            '/js/bootstrap/bootstrap.bundle.min.js'
        ]);
    }

    /**
     * Render Bootstrap CSS and JS.
     *
     * @return string
     */
    public static function renderBootstrap(): string {
        return self::renderBootstrapCss() . self::renderBootstrapJs();
    }

    /**
     * Render jQuery.
     *
     * @return string
     */
    public static function renderjQuery(): string {
        return self::renderJs([
            '/js/jquery/jquery-3.7.1.min.js'
        ]);
    }

    /**
     * Render Font Awesome CSS.
     *
     * @return string
     */
    public static function renderFontAwesome(): string {
        return self::renderFont([
            '/fonts/fa-brands-400.ttf',
            '/fonts/fa-brands-400.woff2',
            '/fonts/fa-regular-400.ttf',
            '/fonts/fa-regular-400.woff2',
            '/fonts/fa-solid-900.ttf',
            '/fonts/fa-solid-900.woff2',
            '/fonts/fa-v4compatibility.ttf',
            '/fonts/fa-v4compatibility.woff2'
        ]);
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