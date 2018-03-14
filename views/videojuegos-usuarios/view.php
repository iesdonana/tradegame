<?php

use app\helpers\Utiles;

use yii\helpers\Html;


$videojuego = $model->videojuego;
?>


<div class="row">
    <div class="pull-right date-publicado">
        <?= Utiles::glyphicon('time') . ' ' .
        Yii::$app->formatter->asRelativeTime($model->created_at) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-offset-1 col-md-3">
        <?= Html::img($videojuego->caratula, ['class' => 'caratula-detail']) ?>
    </div>
    <div class="col-md-6">
        <div class="row">
            <h3 class="well well-sm text-tradegame text-center">
                <?= Html::encode($videojuego->nombre) ?>
            </h3>
        </div>
        <div class="row">
            <div class="col-md-6">
                <strong>Sinopsis:</strong>
                <p><?= Html::encode($videojuego->descripcion) ?></p>
            </div>
            <div class="col-md-6">
                <strong>Comentarios del usuario:</strong>
                <p><?= Html::encode($model->mensaje) ?></p>
            </div>
        </div>
    </div>

</div>
