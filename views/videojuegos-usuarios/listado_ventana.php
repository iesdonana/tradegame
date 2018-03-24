<?php

use app\helpers\Utiles;

use yii\helpers\Html;

$this->registerCssFile('@web/css/listado_videojuegos.css');
?>

<div class="row">
<?php foreach ($listado as $videojuego): ?>
    <?php $videojuego =  $videojuego->videojuego ?>
    <div class="col-md-2 col-xs-3 col-sm-3">
        <?= Html::img($videojuego->caratula, ['class' => 'caratula-mini img-thumbnail center-block']) ?>
        <div class="row text-center text-tradegame">
            <?= Html::encode($videojuego->nombre) ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
