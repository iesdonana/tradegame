<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Mensajes;
use app\models\Valoraciones;
use app\models\OfertasUsuarios;

use app\helpers\Utiles;

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

use kartik\typeahead\Typeahead;

$registroGoogle = Url::to(['usuarios/registrar-google']);
$loginGoogle = Url::to(['site/login-google']);
$baseUrl = Url::to(['videojuegos/buscador-videojuegos']);
$basePath = Url::to(['site/index']);
$langPath = Url::to(['site/cambiar-idioma']);
$js = <<<JS
var baseUrl = "$baseUrl";
var registroGoogle = "$registroGoogle";
var loginGoogle = "$loginGoogle";
var basePath = "$basePath";
var langPath = "$langPath";
JS;
$this->registerJs($js, View::POS_HEAD);
AppAsset::register($this);
$this->title = 'TradeGame';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta name="google-signin-client_id" content="569471360227-qcu0rulbhef1fad6tv00cg9gj1mdihks.apps.googleusercontent.com">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Raleway:500" rel="stylesheet">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/titulo.png', [
                    'alt'=>Yii::$app->name,
                    'height' => '30',
                    'style' => 'display: inline; margin-top: -4px'
                ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    $template =
    "<div title=\'{{nombre}}\'>" .
        "{{nombre}} " .
        "<span class=\'badge\' data-plat=\'{{plataforma}}\'>{{plataforma}}</span>".
    "</div>";
    $url = Yii::$app->request->baseUrl;
    $items = [
        [
            'label' => Utiles::FA('gamepad') . ' ' . Yii::t('app', 'Publicar'),
            'url' => ['/videojuegos-usuarios/publicar']
        ]
    ];

    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => Utiles::FA('sign-in-alt') . ' Login / ' . Yii::t('app', 'Registro'), 'url' => ['/site/login']];
    } else {
        $items[] = [
            'label' => Utiles::FA('list', ['class' => 'fas']) . ' ' . Yii::t('app', 'Mis publicaciones'),
            'url' => ['/videojuegos-usuarios/publicaciones', 'usuario' => Yii::$app->user->identity->usuario]
        ];

        $pendOf = Utiles::badgeNotificacionesPendientes(OfertasUsuarios::className());
        $pendVal = Utiles::badgeNotificacionesPendientes(Valoraciones::className());
        $pendMsg = Utiles::badgeNotificacionesPendientes(Mensajes::className());

        $subItems = [
            [
                'label' => Utiles::FA('handshake', ['class' => 'far']) . " " .
                    Yii::t('app', 'Ofertas') . " $pendOf",
                'url' => ['/ofertas-usuarios/index', 'tipo' => 'recibidas', 'estado' => 'todas']
            ],
            [
                'label' => Utiles::FA('inbox') . " " . Yii::t('app', 'Mensajes') . " $pendMsg",
                'url' => ['/mensajes/listado']
            ],
            [
                'label' => Utiles::FA('star') . " " . Yii::t('app', 'Valoraciones') . " $pendVal",
                'url' => ['/valoraciones/index']
            ]
        ];

        if (Yii::$app->user->identity->esAdmin()) {
            $subItems[] = [
                'label' => Utiles::FA('flag') . " " . Yii::t('app', 'Reportes'),
                'url' => ['/reportes/index']
            ];
        }

        $items[] = [
            'label' => Utiles::FA('bell', ['class' => 'far']) . ' ' . '<span class=\'hidden-md hidden-lg hidden-sm\'>' .  Yii::t('app', 'Notificaciones') . '</span>' . ' '.  Utiles::badgeNotificacionesTotales(),
            'items' => $subItems
        ];

        $form = Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            Utiles::FA('sign-out-alt') . ' ' . Yii::t('app', 'Cerrar sesión'),
            ['class' => 'btn btn-danger btn-block logout']
        )
        . Html::endForm();

        $modelUsuario = Yii::$app->user->identity;
        $linkPerfil = Html::a(
            Html::encode($modelUsuario->usuario), [
                '/usuarios/perfil',
                'usuario' => $modelUsuario->usuario,
            ]
        );
        $items[] = [
            'label' => Utiles::FA('user') . ' ' .
                Html::encode($modelUsuario->usuario),
            'items' => [
                "<div class='navbar-login'>
                    <div class='row'>
                        <div class='col-xs-5 col-sm-5 col-md-4 col-lg-4'>
                            <p class='text-center'>" .
                                Html::img($modelUsuario->usuariosDatos->avatar, ['id' => 'thumbnail-nav'])
                            . "</p>
                        </div>
                        <div class='col-xs-7 col-sm-7 col-md-8 col-lg-8 '>
                            <p class='text-left'><strong>" . $linkPerfil . "</strong></p>
                            <p class='text-left small'>" .
                                Utiles::FA('envelope') . ' ' . Html::encode($modelUsuario->email)
                            . "</p>
                            <p class='text-left'>" .
                                Html::a(
                                    Utiles::FA('cog') . ' ' . Yii::t('app', 'Modificar datos'),
                                    ['usuarios/modificar', 'seccion' => 'datos'],
                                    ['class' => 'btn btn-xs btn-info']
                                )
                            . "</p>
                        </div>
                    </div>
                </div>",
                '<li class="divider"></li>' .
                '<div class="col-md-offset-1 col-md-10">' .
                    $form .
                '</div>'
            ],
            'active' => in_array(Yii::$app->controller->action->id, ['modificar']),
        ];


    }

    $params = Yii::$app->params;
    $currentLang = $params['sourceLanguage'];
    $cookieLang = Yii::$app->getRequest()->getCookies()->getValue('lang');
    if (array_key_exists($cookieLang, $params['languages'])) {
        $currentLang = [
            $cookieLang => $params['languages'][$cookieLang]
        ];
    }

    $subItems = [];
    $keyLang = key($currentLang);
    foreach ($params['languages'] as $key => $value) {
        if ($key !== $keyLang) {
            $subItems[] = [
                'label' => Html::tag('div', Html::img('@web/images/' . $value . '.png', ['class' => 'flag-img', 'data-lang' => $key]) .
                        ' <span class="name-language">' . $value . '</span>', ['class' => 'flag-selectable'])

            ];
        }
    }

    $items[] = [
        'label' => Html::img('@web/images/' . $currentLang[$keyLang] . '.png', ['class' => 'flag-img', 'data-lang' => $keyLang]) . ' ' . ' <span class="name-language hidden-md hidden-lg hidden-sm">' . $currentLang[$keyLang] . '</span>',
        'items' => $subItems
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        'encodeLabels' => false
    ]);
    echo '<form class="navbar-form" role="search">
            <div class="input-group">' .

            Typeahead::widget([
                'name' => 'videojuegos',
                'value' => Yii::$app->request->get('q'),
                'options' => ['placeholder' => Yii::t('app', 'Busca un videojuego ...'), 'class' => 'form-inline'],
                'pluginOptions' => ['highlight'=>true],
                'pluginEvents' => [
                    'typeahead:select' => "function(ev, resp) {window.location.href = '$url/videojuegos/' + resp.id }",
                ],
                'dataset' => [
                    [
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('nombre')",
                        'display' => 'nombre',
                        'templates' => [
                            'notFound' => '<div class="text-danger" style="padding:0 8px">' . Yii::t('app', 'No se ha podido encontrar ningún videojuego') . '</div>',
                            'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                        ],
                        'remote' => [
                            'url' => Url::to(['videojuegos/buscador-videojuegos']) . '?q=%QUERY',
                            'wildcard' => '%QUERY'
                        ]
                    ]
                ]
            ])


            . '
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">
                        <span class="glyphicon glyphicon-search">
                            <span class="sr-only"></span>
                        </span>
                    </button>
                </span>
            </div>
        </form>';
    NavBar::end();
    ?>

    <div class="container custom-container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= \lavrentiev\widgets\toastr\NotificationFlash::widget([
            'options' => [
                'closeButton' => true,
                ]]) ?>
        <div class="main-content">
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <button class='scrollTop btn-circle btn-lg'><?= Utiles::FA('chevron-circle-up') ?></button>
    <div class="container">
        <p class="pull-left">&copy; TradeGame <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
