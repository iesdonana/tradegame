<?php

use app\helpers\Utiles;

use yii\widgets\DetailView;

?>

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
