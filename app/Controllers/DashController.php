<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class DashController extends Controller
{
    public function index($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        } else {
            $posts = Post::orderBy('updated_at', 'desc')->paginate(10);
            $users = User::orderBy('created_at', 'desc')->paginate(10);

            return $this->view->render($response, 'admin/dash.twig', compact('posts', 'users'));
        }
    }
}

