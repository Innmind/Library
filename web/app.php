<?php

use Symfony\Component\{
    HttpFoundation\Request,
    Debug\Debug
};

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';

$env = getenv('SYMFONY_ENV') ?: 'prod';
$env = 'prod';

if ($env === 'dev') {
    Debug::enable();
}

$kernel = new AppKernel($env, $env === 'dev');
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
