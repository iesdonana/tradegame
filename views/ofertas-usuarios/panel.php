<?php
use yii\helpers\Html;
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default panel-trade">
    <li>
        <?= Html::a(Yii::t('app', 'Todas'), [
            'ofertas-usuarios/index',
            ]) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Pendientes'), [
            'ofertas/pendientes',
        ], ['data-seccion' => 'pendientes']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Aceptadas'), [
            'ofertas/aceptadas',
        ], ['data-seccion' => 'aceptadas']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Rechazadas'), [
            'ofertas/rechazadas',
        ], ['data-seccion' => 'rechazadas']) ?>
    </li>
</ul>
