<?php

use app\helpers\Utiles;

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\VideojuegosUsuarios */
/* @var $form yii\widgets\ActiveForm */

$urlDetalles = Url::to(['videojuegos/detalles']);

$this->registerJsFile('@web/js/publicar.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('@web/js/utiles.js');
$this->registerCssFile('@web/css/loader.css');
?>

<div class="videojuegos-usuarios-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?= Html::encode($this->title) ?>
            </div>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
            <?php $url = Url::to(['videojuegos/buscar-videojuegos']) ?>
            <?= $form->field($model, 'videojuego_id')->widget(Select2::classname(), [
                'name' => 'select-videojuegos',
                'language' => 'es',
                'options' => ['placeholder' => 'Busca un videojuego ...'],

                'pluginEvents' => [
                    'select2:select' => "function() {
                        $('#detalles').empty();
                        peticionDetalles('$urlDetalles');
                    }",
                    "select2:unselect" => "function() { $('#detalles').empty(); }"
                ],
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

            <?= $form->field($model, 'mensaje', [
                'template' => "{label}\n{input}\n{hint}\n{error}",
            ])->textarea(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Publicar',
                    ['class' => 'btn btn-tradegame btn-block']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>


</div>
