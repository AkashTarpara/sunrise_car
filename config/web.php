<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'Sunrise Car',
    'name' => 'Sunrise Car',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Kolkata',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@almasaeed2010'   => '@vendor/almasaeed2010',
        //'@webnew'   => '@web/',
    ],
    'defaultRoute' => '/site/index',
    'modules' => [

        'api' => [
            'class' => 'app\modules\api\moduls',
        ],

        'gridview' => [
            'class' => 'kartik\grid\Module',
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'xzq9rbJWY6LRHTOkHZ-dsfsfw',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 'response' => [
        //     'class' => yii\web\Response::class,
        //     'format' => yii\web\Response::FORMAT_JSON,
        //     'on beforeSend' => function ($event) {
        //         $response = $event->sender;
        //         $response->headers->set('Access-Control-Allow-Origin', '*'); // or specific origin
        //         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        //         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, x-xsrf-token');
        //         $response->headers->set('Access-Control-Allow-Credentials', 'true');
        //     },
        // ],
        // 'response' => [
        //     'class' => 'yii\web\Response',
        //     'on beforeSend' => function ($event) {
        //         $response = $event->sender;
        //         $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        //         //$response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self'");
        //         $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        //         $response->headers->set('X-Content-Type-Options', 'nosniff');
        //         $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        //         $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        //     },
        // ],
        'user' => [
            'identityClass' => 'app\models\Appuser',
            'enableAutoLogin' => true,
            'on beforeLogin' => function ($event) {
                //Yii::$app->cache->flush();
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
            'errorAction' => 'site/error',
            //'class' => 'app\components\CustomErrorHandler',
        ],
        'MyFunctions' => [
            'class' => 'app\components\MyFunctions',
        ],

        'PushNotification' => [
            'class' => 'app\components\PushNotification',
        ],

        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD', // Or 'Imagick' if you prefer Imagick
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'main.php',

                        //'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'Yii2Twilio' => [
            'class' => 'filipajdacic\yiitwilio\YiiTwilio',
            'account_sid' => '',
            'auth_key' => '',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'viewPath' => '@app/mail',
            'transport' => [

                'class' => 'Swift_SmtpTransport',
                'host' => ($_ENV['MAIL_HOST'] ?: ''),
                'username' => ($_ENV['SMTP_USER'] ?: ''),
                'password' => ($_ENV['SMTP_PASSWORD'] ?: ''),
                'port' => '587',
                'encryption' => 'tls',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
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
        'db' => $db,
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
                    ],
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        //'web/js/bootstrap.min.js',
                    ],
                ],

                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        //'web/css/bootstrap.min.css',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'rules' => array(
                'dashboard' => 'site/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'userlogin' => 'site/userlogin',
                'userdelete' => 'site/userdelete',
                'resetpassword' => 'site/resetpassword',
                'mobiledetect' => 'site/mobiledetect',
                'mobileapp' => 'site/mobileapp',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'httpclient' => [
            'class' => 'yii\httpclient\Client',
            // Other configurations...
        ],

    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'migrik' => [
                'class' => \insolita\migrik\gii\StructureGenerator::class,
                'templates' => [
                    'custom' => '@app/gii/templates/migrator_schema',
                ],
            ],
            'migrikdata' => [
                'class' => \insolita\migrik\gii\DataGenerator::class,
                'templates' => [
                    'custom' => '@app/gii/templates/migrator_data',
                ],
            ],
        ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
