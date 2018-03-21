<?php
use yii\helpers\Html;
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default">
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
        <?= Html::a('Aceptadas', [
            'valoraciones/valoradas',
        ], ['data-seccion' => 'valoradas']) ?>
    </li>
</ul>
