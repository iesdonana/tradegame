<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-md-offset-4 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    Iniciar sesión
                </div>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'username', [
                        'template' => Utiles::inputGlyphicon('user')
                        ])->textInput([
                            'autofocus' => true,
                            'placeHolder' => 'Usuario'
                        ]) ?>

                    <?= $form->field($model, 'password', [
                        'template' => Utiles::inputGlyphicon('lock')
                        ])->passwordInput(['placeholder' => 'Contraseña']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-tradegame btn-block', 'name' => 'login-button']) ?>
                    </div>
                    <hr>
                    <p>¿No tienes cuenta? Puedes registrate
                        <?= Html::a('aquí', [
                            'usuarios/registrar'
                        ]) ?></p>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
