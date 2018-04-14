<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use app\assets\LoginAsset;

use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

LoginAsset::register($this);

$this->registerJsFile('@web/js/google_login.js', ['position' => View::POS_HEAD]);
$js = <<<JS
$('#form-login a.btn-default').on('click', function() {
    $('#form-login .abcRioButtonContentWrapper').click()
});

$('#form-register a.btn-default').on('click', function() {
    $('#form-register .abcRioButtonContentWrapper').click()
});
JS;
$this->registerJs($js);
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-md-offset-1 col-md-4">
        <div class="panel panel-default panel-trade">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    Iniciar sesión
                </div>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['id' => 'form-login', 'action' => '/site/login']); ?>

                    <?= $form->field($model, 'username', [
                            'template' => Utiles::inputTemplate('user',
                                Utiles::FONT_AWESOME,
                                ['class' => 'fas']
                            )
                        ])->textInput([
                            'autofocus' => true,
                            'placeHolder' => 'Usuario'
                        ]) ?>

                    <?= $form->field($model, 'password', [
                        'template' => Utiles::inputTemplate('key', Utiles::FONT_AWESOME)
                        ])->passwordInput(['placeholder' => 'Contraseña']) ?>
                    <?= Html::a('¿Has olvidado tu contraseña?', ['usuarios/request-recupera']) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-tradegame btn-block', 'name' => 'login-button']) ?>
                    </div>
                    <div class="form-group google-login">
                        <?= Html::a(Html::img('@web/images/google.png') . 'Acceder con Google', null, ['class' => 'btn btn-default btn-block']) ?>
                    </div>

                    <div class="g-signin2 google-btn hidden" data-onsuccess="onSignIn"></div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 site-register">
        <?= $this->render('/usuarios/create', [
            'model' => $modelRegistro
        ]) ?>
    </div>
</div>
