<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use Respect\Validation\Validator as v;
use Illuminate\Pagination\Paginator;

use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;


// #GET ENV
// =========================================================================

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}



// #BOOT APP
// =========================================================================

$app = new Slim\App([
    'settings' => [
        'debug' => getenv('WHOOPS_DEBUG') === 'true',
        'whoops.editor' => 'sublime',
        'displayErrorDetails' => getenv('APP_DEBUG') === 'true',

        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'views' => [
            'cache' => getenv('VIEW_CACHE_DISABLED') === 'true' ? false : __DIR__ . '/../storage/views'
        ],

        'database' => [
            'driver' => getenv('DB_DRIVER'),
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD'),
        ],

    ],
]);



// #PAGINATOR
// =========================================================================

Paginator::currentPathResolver(function(){
    return isset($_SERVER['REQUEST_URI']) ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
});

Paginator::currentPageResolver(function($pageName = 'page') {
    return isset($_GET['page']) ? $_GET['page'] : 1;
});



// #ERROR REPORTING
// =========================================================================

$app->add(new WhoopsMiddleware($app));




// #CONTAINER
// =========================================================================

require_once __DIR__ . '/container.php';



// #ELOQUENT
// =========================================================================

require_once __DIR__ . '/database.php';




// #VALIDATION
// =========================================================================

v::with('App\\Validation\\Rules\\');




// #API
// =========================================================================

require_once __DIR__ . '/../routes/api.php';



// #WEB
// =========================================================================

require_once __DIR__ . '/../routes/web.php';
