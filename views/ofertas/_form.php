<?php

use yii\web\View;
use yii\web\JsExpression;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Ofertas */
/* @var $form yii\widgets\ActiveForm */

$defaultImg = '/' . Yii::getAlias('@caratulas') . '/default.png';
$this->registerJs("var defaultImg = '$defaultImg'", View::POS_HEAD);
// $this->registerJs("$('')")
$this->registerJsFile('@web/js/publicar.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('@web/js/utiles.js');
$this->registerJsFile('@web/js/oferta.js', ['position' => View::POS_HEAD]);
$url = Url::to(['videojuegos-usuarios/buscar-publicados',
    'id_usuario' => Yii::$app->user->id,
    'id_videojuego' => $model->videojuego_publicado_id
]);

$urlDatos = Url::to(['videojuegos/oferta-videojuego']);
?>

<div class="ofertas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'videojuego_publicado_id', ['template' => '{input}'])
        ->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'videojuego_ofrecido_id')->widget(Select2::classname(), [
            'name' => 'select-videojuegos',
            'language' => 'es',
            'options' => ['placeholder' => 'Busca un videojuego ...'],
            'pluginEvents' => [
                'select2:select' => "function() {
                    peticionVideojuego('$urlDatos');
                }",
                "select2:unselect" => "function() { vaciarDatos(); }"
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
        ])->label(false); ?>

    <div class="form-group col-md-offset-2 col-md-8">
        <?= Html::submitButton('Enviar oferta', ['class' => 'btn btn-tradegame btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
