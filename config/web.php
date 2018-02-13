<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'AdminPanel',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'ru-RU',
    'modules' => [
        'manage' => [
            'class' => 'app\modules\manage\Module',
        ],
    ],
    'components' => [
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],
    
        'formatter' => [
            'thousandSeparator' => '',
        ],

        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['user','partner','manager','admin'],
        ],
    
        'request' => [
            'cookieValidationKey' => 'fjeu7TH7R45wuT520Pof78wJhTRvj8Ujhe',
            'enableCookieValidation' => true,
            'enableCsrfCookie' => true,
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'useCookies' => true,
            'timeout' => 3600*24*30,
            'cookieParams' => [
                'httpOnly' => true,
            ]
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'suffix' => '.html',
            'rules' => [
                [
                    'pattern' => 'sitemap',
                    'route' => 'site/sitemap',
                    'suffix' => '.xml',
                ],
                [
                    'pattern' => 'feed',
                    'route' => 'site/feed',
                    'suffix' => '.rss',
                ],
            
                '' => 'site/index',
                '<action:>' => 'site/<action>',
            
                '<controller:>/<action:>/<id:\d+>' => '<controller>/<action>',
                '<controller:>/<action:>' => '<controller>/<action>',
            
                '<module:>/<controller:[\w\-]+>/<action:>/<id:\w+>' => '<module>/<controller>/<action>',
                '<module:>/<controller:[\w\-]+>/<action:>' => '<module>/<controller>/<action>',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:400',
                        'yii\debug\*',
                    ],
                    'message' => [
                        'from' => ['error@admin.test3w.ru' => 'AdminPanel'],
                        'to' => [$params['adminEmail']],
                        'subject' => 'Site error',
                    ],
                    'logVars' => ['_SERVER','_POST','_GET'],
                ],
            ],
        ],
        'db' => require(CONFIG_DIR . '/db.php'), // global secure db-config
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    /** @var $admin_ips array - defined web/index.php */
    
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';
    
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = ['class' => 'yii\gii\Module', 'allowedIPs' => $admin_ips];
}

\Yii::$container->set('yii\grid\GridView', [
    'layout' => '<div class="grid-summary">{summary}</div>{items}<div class="grid-pagination">{pager}</div>',
    'summary' => 'Записи <strong>{begin}</strong>-<strong>{end}</strong> из <strong>{totalCount}</strong>',
    'pager' => [
        'firstPageLabel' => 'Первая',
        'nextPageLabel' => '&rarr;',
        'prevPageLabel' => '&larr;',
        'lastPageLabel' => 'Последняя'
    ],
    'emptyText' => 'Записи не найдены',
    'emptyTextOptions' => ['class' => 'text-center'],
    'tableOptions' => ['class' => 'table table-striped table-hover table-bordered table-condensed'],
    'filterRowOptions' => ['class' => 'form-group-sm row-filters'],
]);

\Yii::$container->set('yii\widgets\ActiveForm', [
    'enableClientValidation' => true,
    'enableClientScript' => true,
    'enableAjaxValidation' => false,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => $params['fieldConfigDefault'],
]);

return $config;
