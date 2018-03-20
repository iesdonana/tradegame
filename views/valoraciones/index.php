<?php

use app\helpers\Utiles;

use yii\grid\ActionColumn;

use yii\helpers\Html;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ValoracionesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis valoraciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valoraciones-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            'usuarioValorado.usuario:text:Usuario a valorar',
            'comentario',
            [
                'class' => ActionColumn::className(),
                'template' => '{valorar}',
                'buttons' => [
                    'valorar' => function ($url, $model, $key) {
                        if ($model->num_estrellas === null) {
                            return Html::a('Valorar ' . Utiles::FA('star'), [
                                'valoraciones/valorar', 'id' => $model->id
                            ], ['class' => 'btn btn-sm btn-warning']);
                        } else {
                            return Utiles::pintarEstrellas($model->num_estrellas);
                        }
                    }
                ]
            ]
        ],
    ]); ?>
</div>
