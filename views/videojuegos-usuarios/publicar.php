<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */

$this->title = 'Publicar videojuego';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videojuegos-usuarios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
