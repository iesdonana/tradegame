<?php

use kartik\datecontrol\Module;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$log = require __DIR__ . '/log.php';

$config = [
    'id' => 'basic',
    'name' => 'TradeGame',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@avatares' => 'uploads/avatares',
        '@caratulas' => 'uploads/caratulas',
        '@fotos_videojuegos' => 'uploads/fotos_videojuegos',
    ],
    'language' => 'es-ES',
    'container' => [
        'definitions' => [
            kartik\select2\Select2::className() => [
                'name' => 'select-videojuegos',
                'options' => ['placeholder' => 'Busca un videojuego ...'],
                'language' => 'es',
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'escapeMarkup' => new yii\web\JsExpression('function (markup) { return markup; }'),
                    'templateSelection' => new yii\web\JsExpression('function (videojuego) { return videojuego.nombre; }'),
                ],
            ],
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'es',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => getenv('MAPS_KEY'),
                        'language' => 'es',
                        'version' => '3.1.18',
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'pih5PdO-ldsms9h0QGfRAHK3yIxny96N',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Usuarios',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // false si se quiere mandar email reales, true si se quiere guardar en un fichero
            'useFileTransport' => false,
            // comment the following array to send mail using php's mail function:
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => $params['adminEmail'],
                'password' => getenv('SMTP_PASS'),
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'log' => $log,
        'db' => $db,
        'formatter' => [
            'timeZone' => 'Europe/Madrid',
            'dateFormat' => $params['dateFormat'],
            'datetimeFormat' => $params['dateTimeFormat'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'videojuegos/modificar/<id:\d+>' => 'videojuegos/update',
                'videojuegos/alta' => 'videojuegos/update',
                'publicacion/<id:\d+>' => 'videojuegos-usuarios/ver',
                'contraoferta/<oferta:\d+>' => 'ofertas/contraoferta',
                'ofrecer/<publicacion:\d+>' => 'ofertas/create',
                'valoraciones' => 'valoraciones/index',
                'valoraciones/todas/<usuario:\w>' => 'valoraciones/listado-notas',
                'valoraciones/buscar/<id:\d+>' => 'valoraciones/buscar',
                'valoraciones/top' => 'top-valoraciones/top',
                'valoraciones/valorar' => 'valoraciones/valorar',
                'valoraciones/<estado:\w+>' => 'valoraciones/index',
                'ofertas/<tipo:\w+>/<estado:\w+>' => 'ofertas-usuarios/index',
                'videojuegos/<id:\d+>' => 'videojuegos/ver',
                'videojuegos/publicar' => 'videojuegos-usuarios/publicar',
                'publicaciones-total' => 'videojuegos-usuarios/all-publicaciones',
                'publicaciones/<usuario:\w+>' => 'videojuegos-usuarios/publicaciones',
                'usuarios/modificar/personal' => 'usuarios-datos/modificar',
                'usuarios/modificar/<seccion:\w+>' => 'usuarios/modificar',
                'registrar' => 'usuarios/registrar',
                'usuario/<usuario:\w+>' => 'usuarios/perfil',
            ],
        ],
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'credentials' => [
                'key' => getenv('KEY_S3'),
                'secret' => getenv('SECRET_S3'),
            ],
            'region' => 'eu-west-3',
            'defaultBucket' => 'tradegame2',
            'defaultAcl' => 'public-read',
        ],
    ],
    'as beforeRequest' => [
        'class' => 'app\components\CheckLanguage'
    ],
    'params' => $params,
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'datecontrol' => [
            'class' => '\kartik\datecontrol\Module',
            'displaySettings' => [
                Module::FORMAT_DATE => $params['dateFormat'],
            ],
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:Y-m-d',
            ],
        ],
    ],
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
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
    $config['components']['mailer']['useFileTransport'] = true;
}

return $config;
