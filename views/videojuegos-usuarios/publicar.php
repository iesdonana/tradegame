<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */

$this->title = 'Publicar videojuego';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videojuegos-usuarios-create">

    <div class="row">
        <div class="col-md-6">
            <?= $this->render('_form', [
                'model' => $model,
                ]) ?>
        </div>
        <div id="detalles" class="col-md-6">
        </div>
    </div>

</div>
