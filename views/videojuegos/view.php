<?php
use app\helpers\Utiles;

use yii\helpers\Html;


$css = <<<CSS
.datos-videojuego {
    margin: 10px;
}
CSS;
$this->registerCss($css);

$this->title = Html::encode($model->nombre);

$this->params['breadcrumbs'][] = [
    'label' => 'Videojuegos',
    'url' => ['videojuegos/buscador-videojuegos']
];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="panel panel-default panel-trade">
            <div class="panel-body">
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->esAdmin()): ?>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <?= Html::a('Modificar ' . Utiles::FA('edit'), ['videojuegos/update', 'id' => $model->id], ['class' => 'btn btn-xs btn-warning']) ?>
                        </div>
                    </div>
                <?php endif ?>
                <?= $this->render('datos', [
                    'model' => $model
                ]) ?>
                <div class="row datos-videojuego">
                    <h4 class="text-tradegame">Videojuegos publicados:</h4>
                </div>
                <div class="row datos-videojuegos">
                    <?= $this->render('/videojuegos-usuarios/publicaciones_videojuego', [
                        'dataProvider' => $dataProvider
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
