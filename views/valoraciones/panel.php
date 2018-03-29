<?php
use app\helpers\Utiles;

use yii\helpers\Html;
?>
<?= Html::a('Top valoraciones ' . Utiles::FA('star'), ['top-valoraciones/top'], ['class' => 'btn btn-warning btn-block']) ?> <br>
<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default filtros panel-trade">
    <li>
        <?= Html::a('Todas', [
            'valoraciones/index',
            ]) ?>
    </li>
    <li>
        <?= Html::a('Pendientes', [
            'valoraciones/pendientes',
        ], ['data-seccion' => 'pendientes']) ?>
    </li>
    <li>
        <?= Html::a('Valoradas', [
            'valoraciones/valoradas',
        ], ['data-seccion' => 'valoradas']) ?>
    </li>
</ul>
