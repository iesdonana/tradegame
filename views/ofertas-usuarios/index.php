<?php

use app\models\VideojuegosUsuarios;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\bootstrap\Modal;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfertasUsuariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$js = <<<JS
$('.popup-modal').click(function(e) {
    e.preventDefault();
    var modal = $('#modal-oferta').modal('show');
    var btn = $('#cambiar-estado');
    var fa = $('<i></i>');
    var cols = $('.popup-modal').first().closest('tr').children();
    var estado = 1;
    if ($(this).data('cambiar') === 'aceptar') {
        fa.addClass('fas fa-check');
        btn.empty().append(fa).append(' Aceptar')
            .removeClass('btn-danger').addClass('btn-success');
        $('.modal-title').html('<span class="text-success">Aceptar oferta</span>');
        $('.modal-text').html(
            '¿<span class="text-success">Aceptas</span> intercambiar tu ' +
            'videojuego ' + cols.eq(1).html() + ' por ' +
            'el videojuego ' + cols.eq(2).html() + ' de ' + cols.eq(3).html() + ' ?'
        );
    } else {
        estado = 0;
        fa.addClass('fas fa-times');
        btn.empty().append(fa).append(' Rechazar')
            .removeClass('btn-success').addClass('btn-danger');
        $('.modal-title').html('<span class="text-danger">Rechazar oferta</span>');
        $('.modal-text').html(
            '¿<span class="text-danger">Rechazas</span> intercambiar tu ' +
            'videojuego ' + cols.eq(1).html() + ' por ' +
            'el videojuego ' + cols.eq(2).html() + ' de ' + cols.eq(3).html() + ' ?'
        );
    }
    $('.modal-footer form').find('input[name=id]').val($(this).closest('tr').data('key'));
    $('.modal-footer form').find('input[name=valor]').val(estado);
});

$('*[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    html: true
});
JS;
$this->registerJs($js);
$this->registerCss('.btn-info {margin-left: 4px;}');
?>
<div class="ofertas-usuarios-index">
    <?= GridView::widget([
        'resizableColumns' => false,
        'responsive' => true,
        'summary' => '',
        'hover' => true,
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
                'label' => 'Videojuego publicado',
                'attribute' => 'publicado',
                'format' => 'raw',
                'value' => function ($model) {
                    $v = VideojuegosUsuarios::findOne($model->id_publicado);
                    return Html::a($model->publicado,
                        ['videojuegos-usuarios/ver', 'id' => $model->id_publicado],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => Html::img($v->videojuego->caratula, ['class' => 'img-miniatura'])
                        ]
                    );
                }
            ],
            [
                'label' => 'Videojuego ofrecido',
                'attribute' => 'ofrecido',
                'format' => 'raw',
                'value' => function ($model) {
                    $v = VideojuegosUsuarios::findOne($model->id_ofrecido);
                    return Html::a($model->ofrecido,
                        ['videojuegos-usuarios/ver', 'id' => $model->id_ofrecido],
                        [
                            'data-toggle' => 'tooltip',
                            'title' => Html::img($v->videojuego->caratula, ['class' => 'img-miniatura'])
                        ]
                    );
                }
            ],
            [
                'label' => 'Ofertante',
                'attribute' => 'usuario_ofrecido',
                'format' => 'raw',
                'value' => function ($model) {
                    $usuario = ($model->contraoferta_de === null) ? $model->usuario_ofrecido : $model->usuario_publicado;
                    return Html::a($usuario, ['usuarios/perfil', 'usuario' => $model->usuario_ofrecido]);
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
                            return Html::a(Utiles::FA('check'), null, [
                                'class' => 'btn btn-xs btn-success popup-modal',
                                'data-cambiar' => 'aceptar',
                                'data-toggle' => 'tooltip',
                                'title' => 'Aceptar oferta',
                            ]);
                        }
                    },
                    'rechazar' => function($url, $model, $key) {
                        if ($model->aceptada === null) {
                            return Html::beginForm(['ofertas/cambiar-estado'], 'post', ['class' => 'accion']) .
                            Html::submitButton(Utiles::FA('times'), [
                                'class' => 'btn btn-xs btn-danger popup-modal',
                                'data-toggle' => 'tooltip',
                                'title' => 'Rechazar oferta',
                                ]) . Html::endForm();
                        }
                    },
                    'contraoferta' => function($url, $model, $key) {
                        if ($model->contraoferta_de === null && $model->aceptada === null) {
                            return Html::a(
                                Utiles::FA('exchange-alt'),
                                ['ofertas/contraoferta', 'oferta' => $model->id],
                                [
                                    'class' => 'btn btn-xs btn-info',
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Realizar contraoferta',
                                ]);
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

    <?php Modal::begin([
     'header' => '<h2 class="modal-title">Oferta</h2>',
     'id'     => 'modal-oferta',
     'footer' => Html::beginForm(['/ofertas/cambiar-estado'], 'post') .
                Html::hiddenInput('id', 0) .
                Html::hiddenInput('valor', -1) .
                 Html::submitButton(
                     '',
                     [
                         'id' => 'cambiar-estado',
                         'class' => 'btn'
                     ]
                 )
                 . Html::endForm()
     ])
     ?>
     <p class='modal-text'>Aquí va el texto</p>
     <?php Modal::end() ?>
</div>
