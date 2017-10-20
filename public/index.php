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

(new Raven_Client('https://2a603045e3ac4318987c2207e5b0bb78:32d0d90b13e543abb006a4464968d0d7@sentry.io/87174', [
    'tags' => [
        'php_version' => phpversion(),
    ],
    'version' => "2.0.1"
]))->install();
// $error_handler->registerExceptionHandler();
// $error_handler->registerErrorHandler(true, [
//         E_ERROR,
//         E_PARSE,
//         E_CORE_ERROR,
//         E_CORE_WARNING,
//         E_COMPILE_ERROR,
//         E_COMPILE_WARNING,
//         E_USER_ERROR,
//         E_USER_WARNING,
//         E_USER_NOTICE,
//         E_STRICT,
//         E_RECOVERABLE_ERROR,
//         E_DEPRECATED,
//         E_USER_DEPRECATED,
//     ]);
// $error_handler->registerShutdownFunction();

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
