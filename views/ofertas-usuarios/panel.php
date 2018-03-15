<?php
use yii\helpers\Html;
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20">
    <li>
        <?= Html::a('Todas', [
            'ofertas-usuarios/index',
            ]) ?>
    </li>
    <li>
        <?= Html::a('Pendientes', [
            'ofertas/pendientes',
        ], ['data-seccion' => 'pendientes']) ?>
    </li>
    <li>
        <?= Html::a('Aceptadas', [
            'ofertas/aceptadas',
        ], ['data-seccion' => 'aceptadas']) ?>
    </li>
    <li>
        <?= Html::a('Rechazadas', [
            'ofertas/rechazadas',
        ], ['data-seccion' => 'rechazadas']) ?>
    </li>
</ul>
