<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'projectsil.ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'authClientCollection' => [
            'class' => yii\authclient\Collection::className(),
            'clients' => [
                'github' => [
                    'class' => 'dektrium\user\clients\GitHub',
                    'clientId' => $_ENV['GITHUB_ID'],
                    'clientSecret' => $_ENV['GITHUB_SECRET'],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $_ENV['COOKIE_VALIDATION_KEY'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser'
            ]
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => '\dektrium\user\models\User',
            'enableAutoLogin' => true,
        ],
        'as access' => [
            'class' => \dektrium\user\filters\AccessRule::className(),
            'allowActions' => [
                'site/error',
                'api/*',
                '/'
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $_ENV['HOST_DOMAINS'],
                'username' => $_ENV['USERNAME_MAIL'],
                'password' => $_ENV['PASSWORD_MAIL'],
                'port' => $_ENV['PORT_MAIL'],
                'encryption' => $_ENV['ENCRYPTION_MAIL'],
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'allow_self_signed' => false
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
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages'
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['upload' => 'api_v1/upload'],
                    'prefix' => 'api/v1',
                    'pluralize' => false,
                ],
            ],
        ],
    ],
    'modules' => [
        'api_v1' => [
            'basePath' => '@app/modules/api/v1',
            'class' => 'app\modules\api\v1\Module'
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['admin']
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'mailer' => [
            'sender' => 'mail@projectsil.ru',
            'welcomeSubject' => 'Welcome subject',
            'confirmationSubject' => 'Confirmation subject',
            'reconfirmationSubject' => 'Email change subject',
            'recoverySubject' => 'Recovery subject',
        ],

    ],
    'params' => $params
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
