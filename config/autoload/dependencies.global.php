<?php

declare(strict_types=1);

return [
    'dependencies' => [
        'aliases' => [
        ],
        'invokables' => [
        ],
        'factories' => [
            'DbAdapter' => Laminas\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],
];
