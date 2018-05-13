<?php
use app\helpers\Utiles;

use yii\helpers\Html;
?>
<?= Html::a(Yii::t('app', 'Top valoraciones') . ' ' . Utiles::FA('star'), ['top-valoraciones/top'], ['class' => 'btn btn-warning btn-block']) ?> <br>
<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default filtros panel-trade">
    <li>
        <?= Html::a(Yii::t('app', 'Todas'), [
            'valoraciones/index',
            ]) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Pendientes'), [
            'valoraciones/pendientes',
        ], ['data-seccion' => 'pendientes']) ?>
    </li>
    <li>
        <?= Html::a(Yii::t('app', 'Valoradas'), [
            'valoraciones/valoradas',
        ], ['data-seccion' => 'valoradas']) ?>
    </li>
</ul>
