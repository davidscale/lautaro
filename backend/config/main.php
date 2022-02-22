<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'rbac' => [
            'class' => 'yii2mod\rbac\Module',
            //'layout' => 'left-menu',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.rbac' => [

                    'class' => 'yii\i18n\PhpMessageSource',

                    'basePath' => '@common/messages',

                    'sourceLanguage' => 'en-US',

                ],
            ],   
        ],'db_guarani' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=170.210.104.126;port=5432;dbname=toba_3_3',
            'username' => 'postgres',
            'password' => 'unlz2020FCA',
            'charset' => 'ISO-8859-1',
            'schemaMap' => [
                'pgsql' => [
                    'class' => 'yii\db\pgsql\Schema',
                    'defaultSchema' => 'negocio' //specify your schema here
                ]
            ], // PostgreSQL
            'on afterOpen' => function ($event) {
                $event->sender->createCommand("SET search_path TO negocio")->execute();
            }
        ],
        'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class,
        'allowActions' => [
            //'site/login',
            //'site/logout',
           'rbac/*',
           'site/*',
           'user/*',
           'admin*'
        ]  
     ], 
     
         'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',  // ej. smtp.mandrillapp.com o smtp.gmail.com
                'username' => 'arm.group.utn@gmail.com',
                'password' => 'pmzaxjfrkoaxihbf',
                'port' => '587', // El puerto 25 es un puerto común también
                'encryption' => 'tls', // Es usado también a menudo, revise la configuración del servidor
            ],
        ],
    ],
    
    'params' => $params,
];
