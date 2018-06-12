<?php
use yii\helpers\Html;

$this->registerCssFile('@web/css/listado_videojuegos.css');
?>

<div class="row">
<?php foreach ($listado as $videojuego): ?>
    <?php $videojuego =  $videojuego->videojuego ?>
    <div class="col-md-2 col-xs-3 col-sm-3">
        <?php $class = 'caratula-mini img-thumbnail center-block' ?>
        <?php if ($videojuego->id === intval($id_videojuego_oferta)): ?>
            <?php $class .= ' disable-image' ?>
        <?php endif; ?>
        <?= Html::img($videojuego->caratula, ['class' => $class]) ?>
        <div class="row text-center text-tradegame">
            <?= Html::encode($videojuego->nombre) ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
