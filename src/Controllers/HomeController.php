<?php

namespace Src\Controllers;

use Exception;
use Src\Http\Request;
use Src\Http\Response;
use Src\Views\View;

/**
 * This class is responsible for handling the home requests
 *
 * Class HomeController
 *
 * @package Src\Controllers
 */
class HomeController {
    /**
     * Show the home page
     *
     * @return void
     * @throws Exception
     */
    public function index(): void {
        View::render('home', [
                'title' => 'Welcome to the Home Page',
            ]
        );
    }
}