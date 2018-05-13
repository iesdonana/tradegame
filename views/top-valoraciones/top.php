<?php

use app\helpers\Utiles;

use yii\helpers\Html;

$css = <<<CSS
.nom {
    margin-top: 20px;
}
h1, h2, h3 {
    display: inline;
}

.row {
    margin-top: 20px;
}

.fa-4x {
    color: yellow;
}

.fa-3x {
    color: grey;
}

.fa-2x {
    color: brown;
}

.text-tradegame {
    font-weight: bold;
}

CSS;
$this->registerCss($css);

$trophy = [
    Utiles::FA('trophy', ['class' => 'fas fa-4x']),
    Utiles::FA('trophy', ['class' => 'fas fa-3x']),
    Utiles::FA('trophy', ['class' => 'fas fa-2x']),
];
?>

<div class="col-md-offset-2 col-md-8">
    <div class="panel panel-default panel-trade">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?= Yii::t('app', 'Top valoraciones') ?>
            </div>
        </div>
        <div class="panel-body">
            <?php if (count($listado) === 0): ?>
                <div class="row text-center">
                    <h3>
                        <?= Yii::t('app', 'Aún no se han realizado valoraciones') ?>
                        <?= Utiles::FA('frown', ['class' => 'far']) ?>
                    </h3>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-2 text-tradegame text-center">
                        <?= Yii::t('app', 'Posición') ?>
                    </div>
                    <div class="col-md-6 text-tradegame">
                        <?= Yii::t('app', 'Usuario') ?>
                    </div>
                    <div class="col-md-4 text-tradegame text-center">
                        <?= Yii::t('app', 'Media de valoración') ?>
                    </div>
                </div>
                <?php for ($i=0; $i < count($listado) ; $i++): ?>
                    <?php
                    $model = $listado[$i];
                    $pos = $i + 1;
                    ?>
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <?php if (isset($trophy[$i])): ?>
                                <?= $trophy[$i] ?>
                            <?php else: ?>
                                <?= $pos ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?= Utiles::tagPosicion(
                                $pos,
                                Html::encode($model->usuario),
                                ['class' => 'nom']
                            ) ?>
                        </div>
                        <div class="col-md-4 text-center">
                            <?= Utiles::tagPosicion(
                                $pos,
                                Html::tag('span', number_format($model->avg, 2), [
                                    'class' => 'label label-default'
                                ])
                            ) ?> <br>
                            <span class="label label-info"><?= Yii::t('app', 'Total') ?>: <?= $model->totales ?></span>
                        </div>
                    </div>
                <?php endfor ?>
            <?php endif; ?>
        </div>
    </div>
</div>
