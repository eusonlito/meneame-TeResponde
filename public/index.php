<?php
define('CACHE_DIR', __DIR__.'/../storage/cache/html');
define('CACHE_FILE', CACHE_DIR.'/'.md5(getenv('REQUEST_URI')));
define('CACHE_ENABLED', is_dir(CACHE_DIR));

if (CACHE_ENABLED && is_file(CACHE_FILE)) {
    readfile(CACHE_FILE) && exit;
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| First we need to get an application instance. This creates an instance
| of the application / container and bootstraps the application so it
| is ready to receive HTTP / Console requests from the environment.
|
*/

$app = require __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$app->run($app['request']);
