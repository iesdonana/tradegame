<?php

use app\models\Videojuegos;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfertasUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$js = <<<JS
$('a[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true
});
JS;
$this->registerJs($js);
?>
<div class="ofertas-usuarios-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            if ($model->aceptada === true) {
                return ['class' => 'success'];
            } elseif ($model->aceptada === false) {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            [
                'attribute' => 'publicado',
                'format' => 'raw',
                'value' => function ($model) {
                    $v = Videojuegos::findOne($model->id_publicado);
                    return Html::a($model->publicado,
                        ['videojuegos/ver', 'id' => $model->id_publicado],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => Html::img($v->caratula, ['class' => 'img-miniatura'])
                        ]
                    );
                }
            ],
            [
                'attribute' => 'ofrecido',
                'format' => 'raw',
                'value' => function ($model) {
                    $v = Videojuegos::findOne($model->id_ofrecido);
                    return Html::a($model->ofrecido,
                        ['videojuegos-usuarios/detalles', 'id' => $model->id_ofrecido],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => Html::img($v->caratula, ['class' => 'img-miniatura'])
                        ]
                    );
                }
            ],
            [
                'attribute' => 'usuario_ofrecido',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->usuario_ofrecido, ['usuarios/perfil', 'usuario' => $model->usuario_ofrecido]);
                }
            ],
            'created_at:datetime:Fecha de recepción',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Operación',
                'template' => '<div class="text-center">{aceptar}{rechazar}</div>',
                'buttons' => [
                    'aceptar' => function($url, $model, $key) {
                        return Html::beginForm(['ofertas/estado']) .
                            Html::submitButton(Utiles::FA('check'), [
                                'class' => 'btn btn-xs btn-success'
                            ]);
                    },
                    'rechazar' => function($url, $model, $key) {
                        return Html::beginForm(['ofertas/estado']) .
                            Html::submitButton(Utiles::FA('times'), [
                                'class' => 'btn btn-xs btn-danger'
                            ]);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
