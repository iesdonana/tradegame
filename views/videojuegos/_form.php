<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Videojuegos */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('@web/js/fotos.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile('@web/css/badge.css');
$this->registerCss('.badge-corner { margin-right: 40px}');
$js = <<<JS
    $('.btn-guardar').on('click', function(e) {
        $('.container-loader').removeClass('hidden');
    });
JS;
$this->registerJs($js);
?>

<div class="videojuegos-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'cargaForm',
            'enctype'=>'multipart/form-data'
        ]
    ]); ?>
    <div class="row">
        <div class="col-md-9">

            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'fecha_lanzamiento')->widget(DateControl::classname(), [
                'readonly' => true,
                'widgetOptions' => [
                    'layout' => '{picker}{input}{remove}',
                    'pluginOptions' => [
                        'autoclose' => true,
                    ]
                ]
                ]) ?>

                <?= $form->field($model, 'desarrollador_id')->dropDownList($desarrolla, ['prompt' => Yii::t('app', 'Selecciona un desarrollador')]) ?>


                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'genero_id')->dropDownList($generos, ['prompt' => Yii::t('app', 'Selecciona un género')]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'plataforma_id')->dropDownList($plataformas, ['prompt' => Yii::t('app', 'Selecciona una plataforma')]) ?>
                    </div>
                </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::img($model->caratula, [
                        'id' => 'img-edit',
                        'class' => 'img img-thumbnail img-responsive img-medium'
                        ]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                        'pluginOptions' => [
                            'showUpload' => false,
                            'showPreview' => false,
                            'showCaption' => false,
                            'showRemove' => false,
                            'browseClass' => 'btn btn-tradegame btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' =>  Yii::t('app', 'Sube la carátula')
                        ],
                        'options' => [
                            'accept' => 'image/*',
                            'class' => 'preview_control'
                        ],
                        ])->label(false);?>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-tradegame btn-block btn-guardar']) ?>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
