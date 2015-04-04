<?php

return [

    'di' => [

        'Router' => [
            'singleton' => true,
            'class' => 'League\Route\RouteCollection',
            'arguments' => [
                'League\Container\Container',
            ],
            'methods' => [
                'setStrategy' => [
                    'League\Route\Strategy\RequestResponseStrategy',
                ],
            ],
        ],
        'Logger' => [
            'singleton' => true,
            'class' => 'Monolog\Logger',
            'arguments' => [
                'songbird',
            ],
        ],
        'Emitter' => [
            'singleton' => true,
            'class' => 'League\Event\Emitter',
        ],
        'RepositoryFactory' => [
            'class' => 'Songbird\File\RepositoryFactory',
        ],

    ],

];