<?php

require __DIR__ . '/../vendor/autoload.php';

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

$error_handler = new Raven_ErrorHandler(
    new Raven_Client('https://2a603045e3ac4318987c2207e5b0bb78:32d0d90b13e543abb006a4464968d0d7@app.getsentry.com/87174', [
        'tags' => [
            'php_version' => phpversion(),
        ]
    ])
);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();

require_once __DIR__ . '/../generated-conf/config.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

define('LOG_FILE', __DIR__ . '/../logs/app.log');
define('LOG_LEVEL', Logger::DEBUG);

$serviceContainer->setLogger('DATA', new Logger('DATA', [new StreamHandler(LOG_FILE, LOG_LEVEL)]));

if (PHP_SAPI == 'cli' && PHP_SAPI !== 'cli-server') {
    // Then
    App\base();
} else {
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
