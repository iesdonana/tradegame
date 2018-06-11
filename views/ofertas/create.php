<?php

use app\helpers\Utiles;

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ofertas */

$this->title = 'Hacer oferta';
$this->params['breadcrumbs'][] = $this->title;

if (isset($usuarioOfrecido)) {
    $nom_ofrecido = $usuarioOfrecido->usuario;
}
$vUsuarioPublicado = $model->videojuegoPublicado;
$videojuegoPublicado = $vUsuarioPublicado->videojuego;

$css = <<<CSS
.tipo-oferta {
    text-transform: uppercase;
    letter-spacing: 4px;
    font-weight: bold;
}
CSS;
$this->registerCss($css);
$this->registerJsFile('@web/js/oferta.js');
$js = <<<JS
$('.pop-listado').click(function(e) {
    e.preventDefault();
    window.open($(this).attr('href'),'title', 'width=600, height=500');
});
JS;
$this->registerJs($js);

?>
<div class="ofertas-create">
    <div class="row text-center">
        <h3>
            <span class="text-tradegame tipo-oferta">
                <?= $model->contraoferta_de === null ? Yii::t('app', 'Oferta') : Yii::t('app', 'Contraoferta') ?>
            </span>
        </h3>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-3 col-sm-3">
            <div class="panel panel-default panel-trade panel-sm">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        <?= Html::encode($videojuegoPublicado->nombre) ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= Html::img($videojuegoPublicado->caratula, [
                            'class' => 'caratula-detail center-block img-thumbnail'
                            ]) ?>
                    </div>
                    <div class="row">
                        <p class="text-center">
                            <?php $usuario = $vUsuarioPublicado->usuario->usuario ?>
                            <strong><?= Yii::t('app', 'Publicado por') ?>:</strong>
                            <?= Html::a(
                                Html::encode($usuario),
                                ['usuarios/perfil', 'usuario' => $usuario]) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-3">
            <?= Html::img('@web/images/trading.png', ['class' => 'trading center-block visible-xs rotado']) ?>
            <?= Html::img('@web/images/trading.png', ['class' => 'trading center-block visible-lg visible-sm visible-md']) ?>
        </div>
        <div class="col-md-3 col-sm-3">
            <div class="panel panel-default panel-trade panel-sm">
                <div class="panel-heading">
                    <div id="mi-oferta-titulo" class="panel-title text-center">
                        &nbsp;
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?= Html::img('/' . Yii::getAlias('@caratulas') . '/default.png', [
                            'id' => 'mi-oferta-caratula',
                            'class' => 'caratula-detail center-block img-thumbnail'
                            ]) ?>
                    </div>
                    <div class="row">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <?php if (isset($usuarioOfrecido)): ?>
                <span>
                    <?= Utiles::FA('info-circle', ['class' => 'fas text-info']) ?>
                    <em>
                        <?= Yii::t('app', 'Puedes ver un listado completo de los videojuegos de {username}', [
                            'username' => Html::encode($nom_ofrecido)
                        ]) ?>
                        <?= Html::a(
                            Yii::t('app', 'aquí'),
                            [
                                'videojuegos-usuarios/publicaciones',
                                'usuario' => $nom_ofrecido,
                                'layout' => 'mini_ventana'
                            ],
                            ['class' => 'pop-listado']
                        ) ?>
                    </em>
                </span>
            <?php endif; ?>
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title">
                        <?= Yii::t('app', 'Ofrecer videojuego') ?>
                    </div>
                </div>
                <div class="panel-body">
                    <?php $datos = ['model' => $model] ?>
                    <?php if (isset($usuarioOfrecido)): ?>
                        <?php $datos = array_merge($datos, ['usuario_id' => $usuarioOfrecido->id]) ?>
                    <?php endif ?>
                    <?= $this->render('_form', $datos) ?>
                </div>
            </div>
        </div>
    </div>


</div>
