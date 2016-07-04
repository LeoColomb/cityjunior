<?php

require __DIR__ . '/../vendor/autoload.php';

if (PHP_SAPI == 'cli-server')
{
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

define('LOGGER_FILE', __DIR__ . '/../logs/app.log');

require_once __DIR__ . '/../generated-conf/config.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$serviceContainer->setLogger('DATA', new Logger('DATA', [new StreamHandler(LOGGER_FILE, Logger::DEBUG)]));

if (PHP_SAPI == 'cli' && PHP_SAPI !== 'cli-server')
{
    // Then
    App\base();
}
else
{
    // Web index
    session_start();

    // Instantiate the app
    $front = new \Slim\App(Front\settings());

    // Load the app
    Front\dependencies($front->getContainer());
    Front\middleware($front);
    Front\routes($front);

    // Run app
    $front->run();
}
