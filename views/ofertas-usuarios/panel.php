<?php
use yii\helpers\Html;

$tipo = Yii::$app->request->get('tipo') !== null ? Yii::$app->request->get('tipo') : 'recibidas';
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default panel-trade">
    <li>
        <?= Html::a(Yii::t('app', 'Todas'), [
            'ofertas-usuarios/index',
            'estado' => 'todas',
            'tipo' => $tipo,
        ], ['data-seccion' => 'todas']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Pendientes'), [
            'ofertas-usuarios/index',
            'estado' => 'pendientes',
            'tipo' => $tipo,
        ], ['data-seccion' => 'pendientes']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Aceptadas'), [
            'ofertas-usuarios/index',
            'estado' => 'aceptadas',
            'tipo' => $tipo,
        ], ['data-seccion' => 'aceptadas']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Rechazadas'), [
            'ofertas-usuarios/index',
            'estado' => 'rechazadas',
            'tipo' => $tipo,
        ], ['data-seccion' => 'rechazadas']) ?>
    </li>
</ul>
