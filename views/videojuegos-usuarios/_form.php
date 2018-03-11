<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="videojuegos-usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'videojuego_id')->textInput() ?>

    <?= $form->field($model, 'mensaje')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
