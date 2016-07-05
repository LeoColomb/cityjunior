<?php

namespace Front;

/**
 * Settings
 * @return array[]
 */
function settings()
{
    return [
        'settings' => [
            'displayErrorDetails' => false, // set to false in production

            // Twig settings
            'view' => [
                'template_path' => __DIR__ . '/../../templates/',
                'cache_path' => false, //__DIR__ . '/../../cache/',
                'base_path' => __DIR__ . '/../../htdocs'
            ],

            // Monolog settings
            'logger' => [
                'name' => 'FRONT',
                'path' => LOG_FILE
            ]
        ],
    ];
}
