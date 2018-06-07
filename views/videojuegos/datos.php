<?php

use app\helpers\Utiles;

use yii\helpers\Html;

?>

<div class="row">
    <div class="page-header text-center text-tradegame">
        <h3><?= Html::a(Html::encode($model->nombre), ['videojuegos/ver', 'id' => $model->id]) ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-3" itemprop="name">
        <?= Html::img($model->caratula, ['class' => 'img-thumbnail center-block caratula-detail']) ?>
    </div>
    <div class="col-md-9">
        <ul class="list-group">
            <li class="list-group-item">
                <strong><?= Yii::t('app', 'Fecha lanzamiento') ?>:</strong>
                <span itemprop="datePublished"><?= Yii::$app->formatter->asDate($model->fecha_lanzamiento) ?></span>
            </li>
            <li class="list-group-item">
                <strong><?= Yii::t('app', 'Plataforma') ?>:</strong>
                <span itemprop="gamePlatform"><?= Html::encode( $model->plataforma->nombre) ?></span>
            </li>
            <li class="list-group-item">
                <strong><?= Yii::t('app', 'Desarrollador') ?>:</strong>
                <?= Html::encode($model->desarrollador->compania) ?>
            </li>
            <li class="list-group-item">
                <strong><?= Yii::t('app', 'Género') ?>:</strong>
                <span itemprop="genre"><?= Html::encode(Yii::t('app',$model->genero->nombre)) ?></span>
            </li>
        </ul>
    </div>
</div>
<div class="row datos-videojuego">
    <strong><?= Yii::t('app', 'Descripción') ?>:</strong><br>
    <span itemprop="about"><?= Html::encode(Utiles::translate($model->descripcion)) ?></span>
</div>
