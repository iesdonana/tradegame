<?php

/* @var $this yii\web\View */
/* @var $model app\models\Videojuegos */
use yii\helpers\Html;


$this->title = 'Update Videojuegos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Videojuegos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['ver', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="videojuegos-update">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title">
                        Modificar videojuego
                    </div>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'generos' => $generos,
                        'plataformas' => $plataformas,
                        'desarrolla' => $desarrolla,
                        ]) ?>
                </div>
        </div>
    </div>
</div>
