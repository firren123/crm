<?php
$params = array_merge(
    require(__DIR__ . '/params.php')
);
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'id' => 'crm-backend',
    'language'=>'zh-CN',
    'name'=>'爱样品500M',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'defaultRoute'=>'admin/site/index',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=10.0.0.10;dbname=shop',
            'username'=>'shop',
            'password'=>'shop',
            'charset'=>'utf8',
        ],
        'db_500m'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=10.0.0.10;dbname=500m_new',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_pay'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=10.0.0.10;dbname=pay',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_social'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=10.0.0.10;dbname=i500_social',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_oa'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=10.0.0.10;dbname=td_oa',
            'username'=>'oadev',
            'password'=>'4fkok0ezZDurw',
            'charset'=>'gbk',
        ],
        'ssdb' => [
            'class' => 'ijackwu\ssdb\Connection',
            'host' => '10.0.0.10',
            'port' => 8888,
//            'auth' => 'kakvi6Zfjsqvddwourzr0wfZjeckqtxj',
//            'timeout' => 2000,
//            'keyPrefix' => 'SOCIAL_API_'
        ],

        'user' => [
            'identityClass' => 'common\models\Admin',
            'enableAutoLogin' => true,

            //'isGuest'=>false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'urlManager'=> [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,

        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ZZ9OX6n9bdunMW3iB-8IYdNGdJAnbMSp',
        ],
    ],

    'params' => $params,
];
