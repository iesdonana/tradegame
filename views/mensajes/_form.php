<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mensajes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mensajes-form">

    <?php $form = ActiveForm::begin(); ?>
    Para <strong><?= $model->receptor->usuario ?> </strong>
    <hr>
    <?= $form->field($model, 'contenido')->textarea([
        'maxlength' => true,
        'rows' => 8,
        'placeholder' => 'Mensaje'
        ])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Enviar', ['class' => 'btn btn-tradegame']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
