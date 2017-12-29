<?php

/*
|--------------------------------------------------------------------------
| #CONTAINER
|--------------------------------------------------------------------------
*/



// #BOOT CONTAINER
// =========================================================================

$container = $app->getContainer();



// #AUTH
// =========================================================================

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};


// #FLASH
// =========================================================================

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};




// #VIEWS
// =========================================================================

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->settings['views']['cache']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
    $view->getEnvironment()->addGlobal('current_path', $container['request']->getUri()->getPath());

    if (isset($_SESSION['user'])) {
        $view->getEnvironment()->addGlobal('auth', [
            'user' => $_SESSION['user'],
        ]);
    }

    // $view->getEnvironment()->addGlobal('auth', [
    //     'check' => $container->auth->check(),
    //     'user' => $container->auth->user(),
    //     'admin' => $container->auth->admin(),
    // ]);

    $view->getEnvironment()->addGlobal('flash', $container['flash']);

    return $view;
};

$twig = $container->view->getEnvironment();




// #ERRORS
// =========================================================================

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container['view']->render($response->withStatus(404), 'errors/404.twig');
    };
};




// #VALIDATION
// =========================================================================

// $container['validator'] = function ($container) {
//     return new App\Validation\Validator;
// };

// $app->add(new \App\Middleware\ValidationErrorsMiddleware($container));




// #MARKDOWN
// =========================================================================

$container['markdown'] = function ($container) {
    return new Parsedown();
};




// #SLUGIFY
// =========================================================================

$container['slug'] = function ($container) {
    return new Cocur\Slugify\Slugify();
};



