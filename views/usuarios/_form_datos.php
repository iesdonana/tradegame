<?php

use app\models\Usuarios;

use app\helpers\Utiles;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-form">
    <?php if ($model->scenario === Usuarios::ESCENARIO_CREATE) {
        $url = Url::to(['usuarios/registrar']);
    } else {
        $url = Url::to(['usuarios/modificar/datos']);
    } ?>
    <?php $form = ActiveForm::begin(['id'=> 'form-register', 'action' => $url, 'options' => [
        'class' => 'cargaForm'
    ]]); ?>

    <?= $form->field($model, 'usuario', [
            'enableAjaxValidation' => true,
            'template' => Utiles::inputTemplate('user', Utiles::FONT_AWESOME)
        ]
        )->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Nombre de usuario')])
        ->label(false) ?>

        <?php
        $config = ['maxlength' => true, 'placeholder' => Yii::t('app', 'Correo electrónico')];
        if ($model->password === null && $model->scenario !== Usuarios::ESCENARIO_CREATE) {
            $config['disabled'] = true;
        }
        ?>
    <?= $form->field($model, 'email', ['template' => Utiles::inputTemplate('envelope', Utiles::FONT_AWESOME)])
            ->textInput($config) ?>

    <?php if ($model->scenario === Usuarios::ESCENARIO_CREATE): ?>
        <?= $form->field($model, 'password', ['template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME)])
            ->passwordInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Contraseña')]) ?>

        <?= $form->field($model, 'repeatPassword', ['template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME)])
            ->passwordInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Vuelve a introducir la contraseña')]) ?>
    <?php endif ?>
    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', $model->scenario) . ' ', ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>
        <?php if ($model->scenario === Usuarios::ESCENARIO_CREATE): ?>
            <div class="form-group google-login">
                <?= Html::a(Html::img('@web/images/google.png') . Yii::t('app', 'Registrarse con Google'), null, ['class' => 'btn btn-default btn-block']) ?>
            </div>
            <div class="g-signin2 google-btn hidden" data-onsuccess="onRegisterIn"></div>
        <?php endif ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
