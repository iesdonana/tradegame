<?php


/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */

$this->title = Yii::t('app', 'Publicar videojuego');
$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS
tr {
    background-color: white;
}
CSS;
$this->registerCss($css);
?>
<div class="videojuegos-usuarios-create">
    <div class="row">
        <div class="col-md-5">
            <?= $this->render('_form', [
                'model' => $model,
                ]) ?>
        </div>
        <div id="detalles" class="col-md-7"></div>
        <div class="loading-container hidden">
            <div class="row">
                <h3 class="text-center"><?= Yii::t('app', 'Cargando detalles...') ?></h3>
            </div>
            <div class="row">
                <div class="loader center-block"></div>
            </div>
        </div>
    </div>
</div>
