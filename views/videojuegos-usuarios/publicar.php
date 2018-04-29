<?php


/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */

$this->title = 'Publicar videojuego';
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
        <div id="detalles" class="col-md-7">
        </div>
    </div>
</div>
