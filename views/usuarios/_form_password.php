<?php

use app\models\Usuarios;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'password', ['template' => Utiles::inputGlyphicon('lock')])
        ->passwordInput(['maxlength' => true, 'placeholder' => 'Nueva contraseña']) ?>

        <?= $form->field($model, 'repeatPassword', ['template' => Utiles::inputGlyphicon('lock')])
            ->passwordInput(['maxlength' => true, 'placeholder' => 'Repite la nueva contraseña']) ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton($model->scenario, ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
