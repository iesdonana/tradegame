<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Videojuegos */

$this->title = 'Create Videojuegos';
$this->params['breadcrumbs'][] = ['label' => 'Videojuegos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videojuegos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
