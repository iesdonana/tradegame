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
$this->registerJsFile('@web/js/publicar.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('@web/js/utiles.js');
$url = Url::to(['videojuegos-usuarios/buscar-publicados',
    'id_usuario' => Yii::$app->user->id,
    'id_videojuego' => $model->videojuego_publicado_id
]);

?>

<div class="ofertas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'videojuego_publicado_id')
        ->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'videojuego_ofrecido_id')->widget(Select2::classname(), [
            'name' => 'select-videojuegos',
            'language' => 'es',
            'options' => ['placeholder' => 'Busca un videojuego ...'],
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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
