<?php

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'oldPassword', [
        'template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME),
        'enableAjaxValidation' => true
        ])->passwordInput(['maxlength' => true, 'placeholder' => 'Contraseña actual']) ?>

    <?= $form->field($model, 'password', ['template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME)])
        ->passwordInput(['maxlength' => true, 'placeholder' => 'Nueva contraseña']) ?>

    <?= $form->field($model, 'repeatPassword', ['template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME)])
        ->passwordInput(['maxlength' => true, 'placeholder' => 'Repite la nueva contraseña']) ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton($model->scenario, ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
