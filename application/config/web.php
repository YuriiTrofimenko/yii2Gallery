<?php

$params = require(__DIR__ . '/params.php');
$basePath = dirname(dirname(__DIR__));

$config = [
    'id' => 'Abram-World',
    'basePath' => $basePath . '/application',
    'vendorPath' => $basePath . '/vendor',
    'runtimePath' => $basePath . '/runtime',
    'defaultRoute' => 'user/profile',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'At9KMm5hGMJbuKXZoPX__HSvrFbrgtOv',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'george.lemish@gmail.com',
                'password' => 'dioralop19851013',
                'port' => '587',
                'encryption' => 'tls',
            ],        ],
        'db' => require(__DIR__ . '/db.php'),
        'assetManager' => [
            'forceCopy' => true
        ]
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

/*
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}
*/
return $config;
