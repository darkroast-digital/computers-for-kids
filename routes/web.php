<?php

/*
|--------------------------------------------------------------------------
| #WEB
|--------------------------------------------------------------------------
*/



use App\Controllers\Auth\AuthController;
use App\Controllers\DashController;
use App\Controllers\HomeController;
use App\Controllers\PostsController;
use App\Controllers\SearchController;
use App\Controllers\SiteController;
use App\Controllers\UsersController;



// #HOME
// =========================================================================

$app->get('/', HomeController::class . ':index')->setName("home");
$app->post('/', HomeController::class . ':post');
$app->get('/login', AuthController::class . ':showLogin');




// #ADMIN
// =========================================================================

$app->group('/admin', function(){
    $this->get('', DashController::class . ':index')->setName("dash");
    // $this->get('/register', AuthController::class . ':showRegister')->setName('register');
    // $this->post('/register', AuthController::class . ':postRegister');
    $this->get('/login', AuthController::class . ':showLogin')->setName("login");
    $this->post('/login', AuthController::class . ':postLogin');
    $this->get('/logout', AuthController::class . ':getLogout')->setName('logout');
    $this->get('/search/{category}', SearchController::class . ':search')->setName('search');
});




// #POSTS
// =========================================================================

$app->group('/admin/posts', function() {
    $this->get('', PostsController::class . ':index')->setName('posts.index');
    $this->get('/create', PostsController::class . ':create')->setName('posts.create');
    $this->post('/create', PostsController::class . ':store');
    $this->get('/edit/{slug}', PostsController::class . ':edit')->setName('posts.edit');
    $this->get('/delete/{slug}', PostsController::class . ':delete')->setName('posts.delete');
    $this->post('/{slug}/update', PostsController::class . ':update')->setName('posts.update');
    $this->get('/{slug}', PostsController::class . ':show')->setName('posts.view');
});




// #USERS
// =========================================================================

$app->group('/admin/users', function(){
    $this->get('', UsersController::class . ':index')->setName('users.index');
    $this->get('/create', UsersController::class . ':create')->setName('users.create');
    $this->post('/create', UsersController::class . ':store');
    $this->get('/edit/{id}', UsersController::class . ':edit')->setName('users.edit');
    $this->get('/delete/{id}', UsersController::class . ':destroy')->setName('users.delete');
    $this->post('/{id}/update', UsersController::class . ':update')->setName('users.update');
    $this->get('/{id}', UsersController::class . ':show')->setName('users.view');
});




// #VIEW POST - USER END
// =========================================================================

$app->get('/{slug}', SiteController::class . ':post')->setName("viewPost");