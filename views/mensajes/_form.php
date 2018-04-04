<?php

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mensajes */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
var formatUsuario = function (usuario) {
    if (usuario.loading) {
        return usuario.usuario;
    }
    var markup =
        '<div class="row">' +
            '<div class="col-sm-12">' +
            usuario.usuario +
            '</div>' +
        '</div>';
    return markup;
};
JS;
$this->registerJs($js, View::POS_HEAD);
?>

<div class="mensajes-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if ($model->receptor_id === null): ?>
        <?= $form->field($model, 'receptor_id')->widget(Select2::classname(), [
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['usuarios/buscar-usuarios']) ,
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('formatUsuario'),
                'templateSelection' => new JsExpression('function (usuario) { return usuario.usuario; }'),
            ],
        ]); ?>
    <?php else: ?>
        Para <strong><?= $model->receptor->usuario ?> </strong>
    <?php endif ?>
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
