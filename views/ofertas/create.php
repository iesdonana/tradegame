<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ofertas */

$this->title = 'Hacer oferta';
$this->params['breadcrumbs'][] = $this->title;

$vUsuarioPublicado = $model->videojuegoPublicado;
$videojuegoPublicado = $vUsuarioPublicado->videojuego;

$this->registerJsFile('@web/js/oferta.js');
?>
<div class="ofertas-create">

    <div class="row">
        <div class="col-md-offset-3 col-md-2 col-sm-offset-2 col-sm-3">
            <div class="row">
                <p class="text-center text-tradegame">
                    <?= Html::encode($videojuegoPublicado->nombre) ?>
                </p>
            </div>
            <div class="row">
                <?= Html::img($videojuegoPublicado->caratula, [
                    'class' => 'caratula-detail center-block img-thumbnail'
                    ]) ?>
            </div>
            <div class="row">
                <p class="text-center">
                    <?php $usuario = $vUsuarioPublicado->usuario->usuario ?>
                    <strong>Publicado por:</strong>
                    <?= Html::a(
                        Html::encode($usuario),
                        ['usuarios/perfil', 'usuario' => $usuario]) ?>
                </p>
            </div>
        </div>
        <div class="col-md-3 col-sm-3">
            <?= Html::img('@web/images/trading.png', ['class' => 'trading center-block visible-xs rotado']) ?>
            <?= Html::img('@web/images/trading.png', ['class' => 'trading center-block visible-lg visible-sm visible-md']) ?>
        </div>
        <div class="col-md-2 col-sm-3">
            <div class="row text-tradegame text-center">
                <p id="mi-oferta-titulo">&nbsp;</p>
            </div>
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
    <hr>
    <div class="col-md-offset-3 col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">
                    Ofrecer videojuego
                </div>
            </div>
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>
            </div>
        </div>
    </div>

</div>
