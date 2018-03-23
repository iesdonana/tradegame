<?php

use yii\helpers\Html;

?>

<div class="row">
    <div class="page-header text-center text-tradegame">
        <h3><?= Html::a($model->nombre, ['videojuegos/ver', 'id' => $model->id]) ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <?= Html::img($model->caratula, ['class' => 'img-thumbnail center-block caratula-detail']) ?>
    </div>
    <div class="col-md-9">
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Fecha lanzamiento:</strong>
                <?= Yii::$app->formatter->asDate($model->fecha_lanzamiento) ?>
            </li>
            <li class="list-group-item">
                <strong>Plataforma:</strong>
                <?= Html::encode($model->plataforma->nombre) ?>
            </li>
            <li class="list-group-item">
                <strong>Desarrollador:</strong>
                <?= Html::encode($model->desarrollador->compania) ?>
            </li>
            <li class="list-group-item">
                <strong>Género:</strong>
                <?= Html::encode($model->genero->nombre) ?>
            </li>
        </ul>
    </div>
</div>
<div class="row datos-videojuego">
    <strong>Descripción:</strong><br>
    <?= Html::encode($model->descripcion) ?>
</div>
