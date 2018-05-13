<?php

use app\helpers\Utiles;

use yii\helpers\Html;

use yii\widgets\DetailView;

?>

<div class="row">
    <div class="col-md-4">
        <?= Html::img($videojuego->caratula, ['class' => 'img-thumbnail caratula-detail center-block']) ?>
        <hr class="visible-xs">
    </div>
    <div class="col-md-8">
        <?= DetailView::widget([
            'model' => $videojuego,
            'attributes' => [
                'nombre',
                'descripcion',
                [
                    'attribute' => 'plataforma.nombre',
                    'label' => Yii::t('app', 'Plataforma'),
                    'format' => 'html',
                    'value' => function ($model) {
                        return Utiles::badgePlataforma($model->plataforma->nombre);
                    }
                ],
                'fecha_lanzamiento:date',
                [
                    'label' => Yii::t('app', 'Género'),
                    'value' => function ($model) {
                        return Yii::t('app', $model->genero->nombre);
                    }
                ]
            ]
            ]) ?>
    </div>
</div>
