<?php

use app\helpers\Utiles;

use yii\helpers\Html;

use yii\widgets\DetailView;

?>

<div class="row">
    <div class="col-md-4">
        <?= Html::img($videojuego->caratula, ['id' => 'caratula-detail', 'class' => 'center-block']) ?>
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
                    'label' => 'Plataforma',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Utiles::badgePlataforma($model->plataforma->nombre);
                    }
                ],
                'fecha_lanzamiento:date:Fecha de lanzamiento',
                'genero.nombre:text:Género'
            ]
            ]) ?>
    </div>
</div>
