<?php

return [
    'name'=>'爱样品500M',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'=>'admin/site/index',
    'timeZone'=>'Asia/Chongqing',
    'modules' => [
        'shop' => [
            'basePath'=>'@backend/modules/shop',
            'class' => 'backend\modules\shop\Module',
            // ... 模块其他配置 ...
        ],
        'admin' => [
            'basePath'=>'@backend/modules/admin',
            'class' => 'backend\modules\admin\Module',

            // ... 模块其他配置 ...
        ],
        'user' => [
            'basePath' => '@backend/modules/user',
            'class' => 'backend\modules\user\Module',
        ],
        'goods' => [
            'basePath' => '@backend/modules/goods',
            'class' => 'backend\modules\goods\Module',
        ],
        'supplier' => [
            'basePath' => '@backend/modules/supplier',
            'class' => 'backend\modules\supplier\Module',
        ],
        'storage' => [
            'basePath' => '@backend/modules/storage',
            'class' => 'backend\modules\storage\Module',
        ],
        //财务模块
        'financial' => [
            'basePath' => '@backend/modules/financial',
            'class' => 'backend\modules\financial\Module',
        ],
        //社交
        'social' => [
            'basePath' => '@backend/modules/social',
            'class' => 'backend\modules\social\Module',
        ],
    ],

];
