<?php

use app\helpers\Utiles;

use yii\helpers\Html;

$css = <<<CSS
.btn-ficha {
    margin-top: 10px;
}
CSS;
$this->registerCss($css);
if (!isset($mostrarBoton)) {
    $mostrarBoton = false;
}
?>

<div class="row">
    <div class="page-header text-center text-tradegame">
        <?php if ($mostrarBoton): ?>
            <h3><?= Html::a(Html::encode($model->nombre), ['videojuegos/ver', 'id' => $model->id]) ?></h3>
        <?php else: ?>
            <h3><?= Html::encode($model->nombre) ?></h3>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col-md-3" itemprop="name">
        <?= Html::img($model->caratula, ['class' => 'center-block caratula-detail']) ?>
        <?php if ($mostrarBoton): ?>
            <div class="row">
                <div class="col-md-12">
                    <?= Html::a('<strong>' . Utiles::FA('info-circle') . ' ' .
                    Yii::t('app', 'Ficha técnica') . '</strong>', [
                        'videojuegos/ver',
                        'id' => $model->id
                    ], ['class' => 'btn btn-xs btn-block btn-primary center-block caratula-detail btn-ficha']) ?>
                </div>
            </div>
        <?php endif; ?>
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
