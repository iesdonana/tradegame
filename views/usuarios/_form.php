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

    <?= $form->field($model, 'usuario', [
            'enableAjaxValidation' => true,
            'template' => Utiles::inputGlyphicon('user')
        ]
        )->textInput(['maxlength' => true, 'placeholder' => 'Nombre de usuario'])
        ->label(false) ?>

    <?= $form->field($model, 'email', ['template' => Utiles::inputGlyphicon('envelope')])
        ->textInput(['maxlength' => true, 'placeholder' => 'Correo electrónico']) ?>

    <?= $form->field($model, 'password', ['template' => Utiles::inputGlyphicon('lock')])
        ->passwordInput(['maxlength' => true, 'placeholder' => 'Contraseña']) ?>

        <?= $form->field($model, 'repeatPassword', ['template' => Utiles::inputGlyphicon('lock')])
            ->passwordInput(['maxlength' => true, 'placeholder' => 'Repite la contraseña']) ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton('Registrar', ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
