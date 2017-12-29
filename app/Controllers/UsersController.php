<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function index($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $users = User::paginate(10);

        return $this->view->render($response, 'admin/users/index.twig', compact('users'));
    }

    public function create($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        if ($this->auth->user()->role != 'admin') {
            $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
            return $response->withRedirect($this->router->pathFor('users.index'));
        }

        return $this->view->render($response, 'admin/users/create.twig');
    }

    public function store($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $params = $request->getParams();
        $password = $params['password'];

        $role = 'user';

        if (isset($params['admin'])) {
            $role = 'admin';
        }

        $user = User::create([
            'name' => $params['name'],
            'password' => password_hash($params['password'], PASSWORD_DEFAULT),
            'position' => $params['position'],
            'email' => $params['email'],
            'phone' => $params['phone'],
            'role' => $role,
        ]);

        $files = $_FILES;
        $image = $files['featured'];

        if (!file_exists(__DIR__ . '/../../assets/uploads/avatars/' . $user->id)) {
            mkdir(__DIR__ . '/../../assets/uploads/avatars/' . $user->id);
            $user->avatar = $user->id;
            $user->save();
        }

        move_uploaded_file($image['tmp_name'], __DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg');

        $this->flash->addMessage('info', 'User Added!');
        return $response->withRedirect($this->router->pathFor('users.index'));
    }

    public function show($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $user = User::where('id', $args['id'])->first();
        $avatar = null;

        if (file_exists(__DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg')) {
            $avatar = __DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg';
        }

        return $this->view->render($response, 'admin/users/user.twig', compact('user', 'avatar'));
    }

    public function edit($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $user = User::where('id', $args['id'])->first();
        $avatar = null;

        if ($this->auth->user()->id != $user->id) {
            if ($this->auth->user()->role != 'admin') {
                $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
                return $response->withRedirect($this->router->pathFor('users.index'));
            }
        }

        if (file_exists(__DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg')) {
            $avatar = __DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg';
            $user->avatar = $user->id;
            $user->save();
        }

        return $this->view->render($response, 'admin/users/edit.twig', compact('user', 'avatar'));
    }

    public function update($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        $params = $request->getParams();

        if (isset($params['password'])) {
            $password = $params['password'];
        }

        $role = 'user';

        if (isset($params['admin'])) {
            $role = 'admin';
        }

        $files = $_FILES;
        $image = $files['avatar'];
        $user = User::where('id', $args['id'])->first();
        $id = $user->id;

        $user->name = $params['name'];
        if (isset($params['password'])) {
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        }
        $user->position = $params['position'];
        if (isset($params['email'])) {
            $user->email = $params['email'];
        }
        if (isset($params['phone'])) {
            $user->phone = $params['phone'];
        }
        $user->role = $role;

        $user->save();

        if (!file_exists(__DIR__ . '/../../assets/uploads/avatars/' . $user->id)) {
            mkdir(__DIR__ . '/../../assets/uploads/avatars/' . $user->id);
            $user->avatar = $user->id;
            $user->save();
        }

        move_uploaded_file($image['tmp_name'], __DIR__ . '/../../assets/uploads/avatars/' . $user->id . '/avatar.jpg');

        $this->flash->addMessage('info', 'User Edited!');

        return $response->withRedirect($this->router->pathFor('users.view', ['id' => $user->id]));
    }

    public function destroy($request, $response, $args)
    {
        if ($this->auth->check() == false) {
            return $this->view->render($response, 'admin/login.twig');
        }

        if ($this->auth->user()->role != 'admin') {
            $this->flash->addMessage('error', 'You do not have the proper permissions for this operation.');
            return $response->withRedirect($this->router->pathFor('users.index'));
        }

        $user = User::find($args['id']);

        $user->delete();

        $this->flash->addMessage('error', 'User Deleted!');
        return $response->withRedirect($this->router->pathFor('users.index'));
    }
}

