<?php

namespace Front;

/**
 * DIC configuration
 * @param \Interop\Container\ContainerInterface $container
 */
function dependencies(\Interop\Container\ContainerInterface $container)
{
    // view renderer
    $container['view'] = function ($container) {
        $settings = $container->get('settings')['view'];
        $view = new \Slim\Views\Twig($settings['template_path'], [
            'cache' => $settings['cache_path']
        ]);
        $view->addExtension(new \Slim\Views\TwigExtension(
            $container['router'],
            $container['request']->getUri()
        ));
        return $view;
    };

    // monolog
    $container['logger'] = function ($container) {
        $settings = $container->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'],
            LOG_LEVEL));
        return $logger;
    };
}
