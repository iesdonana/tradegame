<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-md-offset-4 col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    Iniciar sesión
                </div>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'username', [
                        'template' => '<div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-user"></i>
                                            </span>
                                            {input}
                                       </div>
                                       {error}{hint}'
                        ])->textInput([
                            'autofocus' => true,
                            'placeHolder' => 'Usuario'
                        ]) ?>

                    <?= $form->field($model, 'password', [
                        'template' => '<div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-lock"></i>
                                            </span>
                                            {input}
                                       </div>
                                       {error}{hint}'
                        ])->passwordInput(['placeholder' => 'Contraseña']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
