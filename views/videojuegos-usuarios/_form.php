<?php

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */
/* @var $form yii\widgets\ActiveForm */
$formatJs = <<<JS
var formatVideojuego = function (videojuego) {
    if (videojuego.loading) {
        return videojuego.nombre;
    }
    var markup =
        '<div class="row">' +
            '<div class="col-sm-5">' +
            videojuego.nombre +
            ' <span class="badge">' + videojuego.plataforma.nombre + '</span>'
            '</div>' +
        '</div>';
    return markup;
};
JS;

$this->registerJs($formatJs, View::POS_HEAD);
?>

<div class="videojuegos-usuarios-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php $url = Url::to(['videojuegos/buscar-videojuegos']) ?>
    <?= $form->field($model, 'videojuego_id')->widget(Select2::classname(), [
        'name' => 'select-videojuegos',
        'language' => 'es',
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('formatVideojuego'),
            'templateSelection' => new JsExpression('function (videojuego) { return videojuego.nombre; }'),
        ],
    ]); ?>

    <?= $form->field($model, 'mensaje')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Publicar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
