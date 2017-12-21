<?php

/*
|--------------------------------------------------------------------------
| #ELOQUENT
|--------------------------------------------------------------------------
*/



$config = $container['settings']['database'];

$capsule = New Illuminate\Database\Capsule\Manager;

$capsule->addConnection(array_merge($config, [
    'strict' => false,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci'
]));

$capsule->bootEloquent();
$capsule->setAsGlobal();
