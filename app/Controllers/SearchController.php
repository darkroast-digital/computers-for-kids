<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function results($request, $response, $args)
    {
        //
    }

    public function search($request, $response, $args)
    {
        $query = $request->getParam('q');
        $context = $args['category'];

        if ($args['category'] == 'post') {
            $results = Post::where('title', 'LIKE', '%' . $query . '%')->get();
        } elseif ($args['category'] == 'user') {
            $results = User::where('name', 'LIKE', '%' . $query . '%')->get();
        }

        return $this->view->render($response, 'admin/search/index.twig', compact('results', 'context'));
    }
}

