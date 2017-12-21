<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegister($request, $response, $args)
    {
        
        return $this->view->render($response, 'admin/register.twig');
    }

    public function postRegister($request, $response, $args)
    {
        $user = User::create([
            'name' => $request->getParam('name'),
            'email' => $request->getParam('email'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT)
        ]);

        return $response->withRedirect($this->router->pathFor('login'));
    }

    public function showLogin($request, $response, $args)
    {
        
        return $this->view->render($response, 'admin/login.twig');
    }

    public function postLogin($request, $response, $args)
    {
        $user = User::where('email', $request->getParam('email'))->first();

        if (!$user) {
            // return $response->withRedirect($this->router->pathFor('register'));

            $this->flash->addMessage('error', 'Sorry, that is not a recognized user.');
            return $response->withRedirect($this->router->pathFor('login'));
        }

        if (password_verify($request->getParam('password'), $user->password)) {
            $_SESSION['user'] = $user;
            return $response->withRedirect($this->router->pathFor('dash'));
        } else {
            $this->flash->addMessage('error', 'Incorrect password. Please try again.');
            return $response->withRedirect($this->router->pathFor('login'));
        }
    }

    public function getLogout($request, $response, $args)
    {
        unset($_SESSION['user']);

        $this->flash->addMessage('info', 'You have successfully logged-out!');

        return $response->withRedirect($this->router->pathFor('login'));
    }
}

