<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario', ['enableAjaxValidation' => true])
            ->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'repeatPassword')->passwordInput(['maxlength' => true]) ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton('Registrar', ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
