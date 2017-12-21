<?php

namespace App\Controllers;

use App\Auth;
use App\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class PostsController extends Controller
{
    public function index($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $posts = Post::orderBy('updated_at', 'desc')->paginate(5);

        return $this->view->render($response, 'admin/posts/index.twig', compact('posts'));
    }

    public function show($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $post = Post::where('slug', $args['slug'])->first();

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

        return $this->view->render($response, 'admin/posts/post.twig', compact('post', 'recentPosts', 'featured', 'files'));
    }

    public function get($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $post = Post::where('slug', $args['slug'])->first();

        return $response->withJson($post);
    }

    public function create($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        return $this->view->render($response, 'admin/posts/create.twig');
    }

    public function store($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $user = $this->auth->user();
        $params = $request->getParams();

        if (isset($_FILES['featured'])) {
            // $files = $_FILES['files'];
            // $total = count($files['name']);
            $image = $_FILES['featured'];
        }

        $slug = $this->slug->slugify($params['title']);
        $users = User::all();

        $active = false;

        if ($request->getParam('active') == true) {
            $active = true;
        }

        $post = Post::create([
            'user_id' => $user->id,
            'title' => $params['title'],
            'slug' => $this->slug->slugify($params['title']),
            'author' => $params['author'],
            'body' => $params['body'],
            'active' => $active,
            'page_desc' => $params['page_desc'],
            'page_keys' => $params['page_keys']
        ]);

        $id = $post->id;

        $basePath = __DIR__ . '/../../assets/uploads/posts/' . $id;

        if (isset($_FILES['featured'])) {

            if (!file_exists(__DIR__ . '/../../assets/uploads/posts/' . $id)) {
                mkdir(__DIR__ . '/../../assets/uploads/posts/' . $id);
            }

            move_uploaded_file($image['tmp_name'], __DIR__ . '/../../assets/uploads/posts/' . $id . '/featured.jpg');

            if (!file_exists($basePath . '/files/')) {
                mkdir($basePath . '/files/');
            }

            // for ($i = 0; $i < $total; $i++) {
            //     $filePath = $basePath . '/files/' . $files['name'][$i];
            //     move_uploaded_file($files['tmp_name'][$i], $filePath);
            // }

        }

        $this->flash->addMessage('info', 'Post Created!');

        return $response->withRedirect($this->router->pathFor('posts.index'));
    }

    public function edit($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $post = Post::where('slug', $args['slug'])->first();
        $featured = null;
        $slug = $args['slug'];
        $id = $post->id;

        if ($this->auth->user()->id != $post->user_id) {
            if ($this->auth->user()->role != 'admin') {
                $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
                return $response->withRedirect($this->router->pathFor('posts.index'));
            }
        }

        $active = false;

        if ($post->active == true) {
            $active = true;
        }

        if (file_exists(__DIR__ . '/../../assets/uploads/posts/' . $id . '/featured.jpg')) {
            $featured = '/../../assets/uploads/posts/' . $id . '/featured.jpg';
        }

        return $this->view->render($response, 'admin/posts/edit.twig', compact('post', 'featured', 'visible'));
    }

    public function update($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $params = $request->getParams();
        $active = false;

        if (isset($params['active'])) {
            $active = true;
        }

        $files = $_FILES;
        $image = $files['featured'];
        $slug = $this->slug->slugify($params['title']);
        $post = Post::where('slug', $args['slug'])->first();
        $id = $post->id;

        if (!file_exists(__DIR__ . '/../../assets/uploads/posts/' . $id)) {
            mkdir(__DIR__ . '/../../assets/uploads/posts/' . $id);
        }

        move_uploaded_file($image['tmp_name'], __DIR__ . '/../../assets/uploads/posts/' . $id . '/featured.jpg');

        $post->title = $params['title'];
        $post->slug = $this->slug->slugify($params['title']);
        $post->body = $params['body'];
        $post->author = $params['author'];
        $post->active = $active;
        $post->page_desc = $params['page_desc'];
        $post->page_keys = $params['page_keys'];

        $post->save();

        $this->flash->addMessage('info', 'Post Edited!');

        return $response->withRedirect($this->router->pathFor('posts.edit', ['slug' => $post->slug]));
    }

    public function trash($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        if ($this->auth->user()->role != 'admin') {
            $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
            return $response->withRedirect($this->router->pathFor('posts.index'));
        }

        $post = Post::find($args['id']);

        $post->delete();
    }

    public function delete($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        if ($this->auth->user()->role != 'admin') {
            $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
            return $response->withRedirect($this->router->pathFor('posts.index'));
        }

        $slug = $args['slug'];
        $post = Post::where('slug', $slug)->first();
        $id = $post->id;
        $path = __DIR__ . '/../../assets/uploads/posts/' . $id;

        function rrmdir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (is_dir($dir . "/" . $object)) {
                            rrmdir($dir . "/" . $object);
                        } else {
                            unlink($dir . "/" . $object);
                        }
                    }
                }

                rmdir($dir);
            }
        }

        rrmdir($path);
        $post->active = 0;
        $post->save();

        $post->delete();

        $this->flash->addMessage('error', 'Post Deleted!');
        return $response->withRedirect($this->router->pathFor('posts.index'));
    }
}

