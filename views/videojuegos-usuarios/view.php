<?php

use app\helpers\Utiles;

use yii\helpers\Html;

$videojuego = $model->videojuego;
?>

<div class="row">
    <div class="row">
        <div class="col-md-offset-10 col-md-2 col-xs-offset-4 col-xs-6 date-publicado">
            <?= Utiles::FA('clock', ['class' => 'far']) . ' ' .
            Yii::$app->formatter->asRelativeTime($model->created_at) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-1 col-md-3">
            <div class="text-center">
                <div class="row">
                    <?= Html::img($videojuego->caratula, ['class' => 'caratula-detail center-block']) ?>
                </div>
                <div class="row detalles-info text-center">
                    <?= Html::a('Detalles del videojuego', null, ['class' => 'btn btn-xs btn-default']) ?>
                </div>
            </div>
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
                    <?php $desc = mb_strimwidth($videojuego->descripcion, 0, 300, '...')?>

                    <p>
                        <?= Html::encode($desc) ?>
                        <?php if (mb_strlen($desc) === 300): ?>
                            <?= Html::a('Ver mas', null, ['class' => 'btn btn-xs btn-default']) ?>
                        <?php endif ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <strong>Comentarios del usuario:</strong>
                    <p><?= Html::encode($model->mensaje) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php if ($model->usuario_id !== Yii::$app->user->id): ?>
    <div class="row">
        <div class="col-md-offset-10 col-md-2 col-xs-offset-4 col-xs-6">
            <?= Html::a('Hacer oferta', [
                'ofertas/create',
                'publicacion' => $model->id
            ], ['class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php endif ?>
</div>
