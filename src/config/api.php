<?php

$db = require __DIR__ . '/db.php';
$queue = require __DIR__ . '/queue.php';
$redis = require __DIR__ . '/redis.php';
$definitions = require __DIR__ . '/definitions.php';

return [
    'id' => 'api-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'queue',
        function () {
            Yii::$container->get(\app\infrastructure\adapters\events\EventSubscriber::class);
        },
    ],
    'controllerNamespace' => 'app\\api\\controllers\\v1',
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ],
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY') ?: 'f34de9f72c90102b7a8c4d85a2e3f2180c7c9e34a0f6763bb9d1ab7cfb9e4f61',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'POST v1/users/<id>/requests' => 'user/requests',
                [
                    'class' => \yii\rest\UrlRule::class,
                    'controller' => ['user'],
                    'prefix' => 'v1',
                    'pluralize' => true,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\\infrastructure\\database\\models\\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['info'],
                    'logFile' => '@runtime/logs/info.log',
                    'categories' => ['app.requests'],
                    'logVars' => [],
                ],
            ],
        ],
        'db' => $db,
        'queue' => $queue,
        'redis' => $redis,
    ],
    'container' => [
        'definitions' => $definitions,
    ],
];
