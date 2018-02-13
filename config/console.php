<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests/codeception');
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => require(CONFIG_DIR . '/db.php'), // global secure db-config
    ],
    'params' => $params,
];

return $config;
