<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'console\controllers',
    'timeZone'=>'Asia/Chongqing',

    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=shop',
            'username'=>'shop',
            'password'=>'shop',
            'charset'=>'utf8',
        ],
        'db_500m'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=500m_new',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_pay'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.55;dbname=pay',
            'username'=>'500m',
            'password'=>'500m',
            'charset'=>'utf8',
        ],
        'db_oa'    => [
            'class'=>'yii\db\Connection',
            'dsn'=>'mysql:host=118.186.247.57;port=3336;dbname=td_oa',
            'username'=>'oadev',
            'password'=>'4fkok0ezZDurw',
            'charset'=>'utf8',
        ],
    ],
];
