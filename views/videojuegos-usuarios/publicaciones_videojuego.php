<?php
use app\helpers\Utiles;

use yii\helpers\Html;

use kartik\grid\GridView;
/**
 * Muestra un listado de videojuegos publicados por los usuarios, de un videojuego
 * concreto
 */
?>

<?= GridView::widget([
    'containerOptions' => [
        'class' => 'datos-videojuego'
    ],
    'responsive' => true,
    'responsiveWrap' => true,
    'summary' => '',
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => Yii::t('app', 'Usuario'),
            'format' => 'raw',
            'value' => function ($model) {
                $usr = $model->usuario->usuario;
                return Html::a(Html::encode($usr), [
                    'usuarios/perfil',
                    'usuario' => $usr
                ]);
            }
        ],
        [
            'label' => Yii::t('app', 'Comentarios'),
            'width' => '400px',
            'format' => 'raw',
            'contentOptions' => ['class' => 'comentarios-videojuego'],
            'value' => function ($model) {
                if ($model->mensaje == '') {
                    return Html::tag('em', Yii::t('app', 'No se ha proporcionado ningún comentario'));
                }
                return Html::encode(Utiles::translate($model->mensaje));
            }
        ],
        [
            'label' => Yii::t('app', 'Publicado'),
            'format' => 'html',
            'attribute' => 'created_at',
            'value' => function ($model) {
                return Html::a(Yii::$app->formatter->asRelativeTime($model->created_at), ['videojuegos-usuarios/ver', 'id' => $model->id]);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Operación'),
            'template' => '<div class="text-center">{oferta}</div>',
            'buttons' => [
                'oferta' => function ($url, $model, $key) {
                    if ($model->usuario_id !== Yii::$app->user->id) {
                        return Html::a(Yii::t('app', 'Hacer oferta'), [
                            'ofertas/create',
                            'publicacion' => $model->id
                        ], ['class' => 'btn btn-xs btn-warning']);
                    } else {
                        return Html::a(Yii::t('app', 'Tu publicación'), null, [
                            'class' => 'btn btn-xs btn-primary',
                            'disabled' => 'disabled',
                        ]);
                    }
                }
            ]
        ]
    ]
]) ?>
