<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\OfertasUsuarios;

use app\helpers\Utiles;

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
$this->title = 'TradeGame';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/logo.png', [
                    'alt'=>Yii::$app->name,
                    'width' => '25px',
                    'style' => 'display: inline; margin-right: 10px'
                ]) . Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    $items = [
        [
            'label' => Utiles::FA('gamepad') .
                ' Publicar',
            'url' => ['/videojuegos-usuarios/publicar']
        ]
    ];

    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => Utiles::FA('sign-in-alt') . ' Login / Registro', 'url' => ['/site/login']];
    } else {
        $items[] = [
            'label' => Utiles::FA('list', ['class' => 'fas']) . ' Mis publicaciones',
            'url' => ['/videojuegos-usuarios/publicaciones', 'usuario' => Yii::$app->user->identity->usuario]
        ];
        if (($pendOf = OfertasUsuarios::getPendientes()) > 0) {
            $pendOf = Html::tag('span', $pendOf, ['class' => 'badge badge-custom']);
        } else {
            $pendOf = '';
        }
        $items[] = [
            'label' => Utiles::FA('bell', ['class' => 'far']) . ' Notificaciones ' . $pendOf,
            'items' => [
                [
                    'label' => Utiles::FA('handshake', ['class' => 'far']) . ' Ofertas ' . $pendOf,
                    'url' => ['/ofertas-usuarios/index']
                ],
                [
                    'label' => Utiles::FA('comments', ['class' => 'far']) . ' Mensajes',
                    'url' => ['/mensajes/index']
                ]
            ]
        ];
        $form = Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            Utiles::FA('sign-out-alt') . ' Cerrar sesión',
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
                                    Utiles::FA('cog') . ' Modificar datos',
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
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <button class='scrollTop'><?= Utiles::FA('chevron-circle-up') ?></button>
    <div class="container">
        <p class="pull-left">&copy; TradeGame <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
