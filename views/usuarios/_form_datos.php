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
    <?php
    $pass = 'Contraseña';
    $pass2 = 'Repite la contraseña';
    if ($model->scenario === Usuarios::ESCENARIO_UPDATE) {
        $pass = 'Nueva contraseña';
        $pass2 = 'Repite la nueva contraseña';
    } ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'usuario', [
            'enableAjaxValidation' => true,
            'template' => Utiles::inputGlyphicon('user')
        ]
        )->textInput(['maxlength' => true, 'placeholder' => 'Nombre de usuario'])
        ->label(false) ?>

    <?= $form->field($model, 'email', ['template' => Utiles::inputGlyphicon('envelope')])
        ->textInput(['maxlength' => true, 'placeholder' => 'Correo electrónico']) ?>
    <!-- <?php if ($model->scenario === Usuarios::ESCENARIO_UPDATE): ?>
        <h4 class='text-center'>Cambiar contraseña</h4>
    <?php endif ?>
    <?= $form->field($model, 'password', ['template' => Utiles::inputGlyphicon('lock')])
        ->passwordInput(['maxlength' => true, 'placeholder' => $pass]) ?>

        <?= $form->field($model, 'repeatPassword', ['template' => Utiles::inputGlyphicon('lock')])
            ->passwordInput(['maxlength' => true, 'placeholder' => $pass2]) ?> -->

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton($model->scenario, ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
