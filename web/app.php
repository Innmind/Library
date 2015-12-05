<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$env = getenv('SYMFONY_ENV') ?: 'prod';
$debug = $env === 'dev';

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

if ($debug === true) {
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel($env, $debug);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
