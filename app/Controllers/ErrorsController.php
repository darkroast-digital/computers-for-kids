<?php

namespace App\Controllers;

use App\Controllers\Controller;

class ErrorsController extends Controller
{
    public function error404($request, $response, $args)
    {
        return $this->view->render($response, 'errors/404.twig');
    }
}

