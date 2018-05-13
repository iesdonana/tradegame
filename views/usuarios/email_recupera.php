<?php
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\widgets\ActiveForm;
?>

<div class="col-md-offset-2 col-md-8">
    <div class="panel panel-default panel-trade">
        <div class="panel-heading">
            <div class="panel-title">
                <?= Yii::t('app', 'Recuperar contraseña') ?>
            </div>
        </div>
        <div class="panel-body">
            Introduce tu email para poder enviar un correo electrónico con los pasos y poder recuperar tu contraseña.
            <?php $form = ActiveForm::begin([
               'id' => 'recupera-form',
               'method' => 'post',
               'action' => ['usuarios/request-recupera'],
           ]) ?>
           <br>
           <?= $form->field($model, 'email',
                ['template' => Utiles::inputTemplate('envelope', Utiles::FONT_AWESOME)])
                ->textInput(['placeholder' => 'Correo electrónico']) ?>

            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="form-group">
                        <?= Html::submitButton('Enviar correo', ['class' => 'btn btn-tradegame btn-block']) ?>
                    </div>
                </div>
            </div>
           <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
