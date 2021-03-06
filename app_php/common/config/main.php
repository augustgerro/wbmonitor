<?php

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'ru-RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'pgsql:host=postgres;port=5432;dbname=' . getenv('POSTGRES_DB'),
            'username' => getenv('POSTGRES_USER'),
            'password' => getenv('POSTGRES_PASSWORD'),
            'schemaMap' => [
                'pgsql' => [
                    'class' => \yii\db\pgsql\Schema::class,
                    'columnSchemaClass' => [
                        'class' => \yii\db\pgsql\ColumnSchema::class,
                        'deserializeArrayColumnToArrayExpression' => false,
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => \yii\redis\Cache::class,
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'redis',
        ],
        'rabbitmq' => [
            'class' => \mikemadisonweb\rabbitmq\Configuration::class,
            'connections' => [
                [
                    'host' => 'rabbitmq',
                    'port' => '5672',
                    'user' => getenv('RABBITMQ_DEFAULT_USER'),
                    'password' => getenv('RABBITMQ_DEFAULT_PASSWORD'),
                    'vhost' => '/',
                ]
            ],
        ],
        'productService' => [
            'class' => \common\services\ProductService::class,
        ],
    ],
];
