<?php

/* @var $this yii\web\View */
/* @var $model app\models\Videojuegos */
use app\helpers\Utiles;


$scenario = ($model->id === null) ? Yii::t('app', 'Alta de videojuego') : Yii::t('app', 'Modificar videojuego');
$this->title = $scenario;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Videojuegos'),
    'url' => ['videojuegos/buscador-videojuegos']
];
if ($model->id !== null) {
    $this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['ver', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/pacman.css');
?>
<div class="videojuegos-update">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title">
                        <?= $this->title ?>
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
<?= Utiles::loaderPacman() ?>
