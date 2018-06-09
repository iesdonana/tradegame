<?php

use app\helpers\Utiles;

use yii\grid\ActionColumn;

use yii\helpers\Html;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ValoracionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Mis valoraciones');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valoraciones-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '<span class="text-warning">' . Yii::t('app', 'Pendiente de valorar') . '</span>'
        ],
        'columns' => [
            [
                'header' => Yii::t('app', 'Usuario a valorar'),
                'format' => 'raw',
                'value' => function ($model) {
                    $usr = $model->usuarioValorado->usuario;
                    return Html::a(Html::encode($usr), ['usuarios/perfil', 'usuario' => $usr]);
                }
            ],
            [
                'header' => Yii::t('app', 'Comentario'),
                'value' => function ($model) {
                    if ($model->comentario !== null) {
                        return Html::encode(Utiles::translate($model->comentario));
                    }
                }
            ],
            [
                'header' => Yii::t('app', 'Valoración'),
                'class' => ActionColumn::className(),
                'template' => '{valorar}',
                'headerOptions' => ['style' => 'width:20%'],
                'buttons' => [
                    'valorar' => function ($url, $model, $key) {
                        if ($model->num_estrellas === null) {
                            return Html::a(Yii::t('app', 'Valorar') . ' ' . Utiles::FA('star'), [
                                'valoraciones/valorar', 'id' => $model->id
                            ], ['class' => 'btn btn-sm btn-valora']);
                        } else {
                            return Utiles::pintarEstrellas($model->num_estrellas);
                        }
                    }
                ]
            ]
        ],
    ]); ?>
</div>
