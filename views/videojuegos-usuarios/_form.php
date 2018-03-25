<?php

use app\helpers\Utiles;

use yii\web\View;

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
$js = <<<JS
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
JS;
$this->registerJs($js);
?>

<div class="videojuegos-usuarios-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?= Html::encode($this->title) ?>
            </div>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['options' => [
                'class' => 'cargaForm'
            ]]); ?>
            <?php $url = Url::to(['videojuegos/buscar-videojuegos']) ?>
            <?php $items = [
                'pluginEvents' => [
                    'select2:select' => "function() {
                        $('#detalles').empty();
                        peticionDetalles('$urlDetalles');
                    }",
                    "select2:unselect" => "function() { $('#detalles').empty(); }"
                ]
            ];
            $items = array_merge($items, Utiles::optionsSelect2($url)) ?>

            <?= $form->field($model, 'videojuego_id')->widget(Select2::classname(), $items); ?>

            <?= $form->field($model, 'mensaje', [
                'template' => "{label} " . Utiles::glyphicon('info-sign', 'Comentarios acerca del videojuego. Por ejemplo: tu opinión personal.') .
                            "\n{input}\n{hint}\n{error}",
            ])->textarea(['maxlength' => true, 'rows' => 8]) ?>

            <div class="form-group">
                <?= Html::submitButton('Publicar ',
                    ['class' => 'btn btn-tradegame btn-block']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
