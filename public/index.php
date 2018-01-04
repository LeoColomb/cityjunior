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

date_default_timezone_set('Europe/Paris');

if ($_ENV['CUSTOMCONNSTR_SENTRY_DSN']) {
    (new Raven_Client($_ENV['CUSTOMCONNSTR_SENTRY_DSN'], [
        'tags' => [
            'php_version' => phpversion(),
        ],
        'version' => "2.0.1"
    ]))->install();
}

require_once __DIR__ . '/../generated-conf/config.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

define('LOG_FILE', __DIR__ . '/../logs/app.log');
define('LOG_LEVEL', Logger::DEBUG);

$serviceContainer->setLogger('DATA', new Logger('DATA', [new StreamHandler(LOG_FILE, LOG_LEVEL)]));

if ((PHP_SAPI == 'cli' && PHP_SAPI !== 'cli-server') || array_key_exists('pull', $_GET)) {
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
