<?php

use app\models\Videojuegos;

use app\helpers\Utiles;

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfertasUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$js = <<<JS
$('*[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true
});
JS;
$this->registerJs($js);
?>
<div class="ofertas-usuarios-index">
    <?= GridView::widget([
        'responsive' => true,
        'summary' => '',
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model) {
            if ($model->aceptada === true) {
                return ['class' => 'success'];
            } elseif ($model->aceptada === false) {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            [
                'label' => 'Tipo',
                'attribute' => 'contraoferta_de',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->contraoferta_de === null) {
                        return Html::tag('span', 'Oferta', ['class' => 'label label-default center-block']);
                    }
                    return Html::tag('span', 'Contraoferta', ['class' => 'label label-primary center-block']);

                }

            ],
            [
                'label' => 'Mi videojuego',
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
                'label' => 'Videojuego ofrecido',
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
                'label' => 'Usuario',
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
                'template' => '<div class="text-center">{aceptar}{rechazar}{contraoferta}{estado}</div>',
                'buttons' => [
                    'aceptar' => function($url, $model, $key) {
                        if ($model->aceptada === null) {
                            return Html::beginForm(['ofertas/cambiar-estado'], 'post', ['class' => 'accion']) .
                            Html::submitButton(Utiles::FA('check'), [
                                'class' => 'btn btn-xs btn-success',
                                'data-toggle' => 'tooltip',
                                'title' => 'Aceptar oferta',
                                ]) . Html::endForm();
                        }
                    },
                    'rechazar' => function($url, $model, $key) {
                        if ($model->aceptada === null) {
                            return Html::beginForm(['ofertas/cambiar-estado'], 'post', ['class' => 'accion']) .
                            Html::submitButton(Utiles::FA('times'), [
                                'class' => 'btn btn-xs btn-danger',
                                'data-toggle' => 'tooltip',
                                'title' => 'Rechazar oferta',
                                ]) . Html::endForm();
                        }
                    },
                    'contraoferta' => function($url, $model, $key) {
                        if ($model->contraoferta_de === null && $model->aceptada === null) {
                            return Html::beginForm(['ofertas/contraoferta'], 'post', ['class' => 'accion']) .
                            Html::submitButton(Utiles::FA('exchange-alt'), [
                                'class' => 'btn btn-xs btn-info',
                                'data-toggle' => 'tooltip',
                                'title' => 'Realizar contraoferta',
                                ]) . Html::endForm();
                        }
                    },
                    'estado' => function ($url, $model, $key) {
                        if ($model->aceptada === true) {
                            return Utiles::FA('handshake', [
                                'class' => 'far fa-2x text-success',
                                'tooltip' => 'Oferta aceptada',
                            ]);
                        } else if ($model->aceptada === false) {
                            return Utiles::FA('ban', [
                                'class' => 'fas fa-2x text-danger',
                                'tooltip' => 'Oferta rechazada',
                            ]);
                        }
                    }
                ],
                'contentOptions'=>['style'=>'width: 100px;']
            ],
        ],
    ]); ?>
</div>
