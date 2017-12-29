<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class SiteController extends Controller
{

    public function post($request, $response, $args)
    {
        $activePosts = Post::where('active', 1)->get();

        $post = Post::where('slug', $args['slug'])->first();

        if ($post == false) {
            return $response->withRedirect($this->router->pathFor('error404'));
        }

        $featured = null;
        $id = $post->id;
        $slug = $args['slug'];

        if (file_exists(__DIR__ . '/../../assets/uploads/posts/' . $id . '/featured.jpg')) {
            $featured = '/assets/uploads/posts/' . $id . '/featured.jpg';
        }

        $files = [];

        if (count(glob(__DIR__ . '../../assets/uploads/posts/' . $id . '/files/*'))) {
            $scan = scandir(__DIR__ . '../../assets/uploads/posts/' . $id . '/files');
            unset($scan[0]);
            unset($scan[1]);

            foreach ($scan as $file) {
                array_push($files, $file);
            }
        }

        $recentPosts = Post::orderBy('updated_at', 'desc')->limit(3)->get();

        $post->body = $this->markdown->text($post->body);

        return $this->view->render($response, 'post.twig', compact('post', 'recentPosts', 'featured', 'files'));
    }

}