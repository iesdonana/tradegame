<?php

use app\helpers\Utiles;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\LinkPager;
$this->registerCssFile('@web/css/top-valoraciones.css');
$this->params['breadcrumbs'][] = Yii::t('app', 'Top valoraciones');

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
                <table class="table table-hover">
                    <tr>
                        <th class="text-center"><?= Yii::t('app', 'Posición') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Usuario') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Media de valoración') ?></th>
                    </tr>
                    <?php for ($i=0; $i < count($listado) ; $i++): ?>
                        <?php
                        $model = $listado[$i];
                        $pos = $i + $pages->offset + 1;
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php if ($pos > 3): ?>
                                    <div class="pos"><?= $pos ?></div>
                                <?php else: ?>
                                    <div class="pos">
                                        <?= Html::img('@web/images/' . $pos . '.png', ['height' => 40]) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="username">
                                    <?= Html::a(Html::encode($model->usuario), Url::to(['usuarios/perfil', 'usuario' => $model->usuario])) ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="media">
                                    <?= Html::tag('span', number_format($model->avg, 2), [
                                        'class' => 'label label-default'
                                    ]) ?>
                                </div>
                                <span class="num-valoraciones" title="Total de valoraciones"><?= $model->totales ?></span>
                            </td>
                        </tr>
                    <?php endfor ?>
                </table>
                <div class="col-md-12 text-center">
                    <?= LinkPager::widget([
                        'pagination' => $pages,
                    ]); ?>                    
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
