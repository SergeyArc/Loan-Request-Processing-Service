<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/api.php';

if (YII_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

(new yii\web\Application($config))->run();