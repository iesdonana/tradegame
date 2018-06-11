<?php

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Reportes */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile('@web/js/utiles.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="reportes-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class' => 'cargaForm'
        ]
    ]); ?>

    <?= $form->field($model, 'reportado_id')->hiddenInput(['value' => 1])->label(false) ?>

    <?= $form->field($model, 'mensaje', [
        'template' => "{label} " . Utiles::contadorCaracteres(20) . "\n{input}\n{hint}\n{error}"
        ])->textarea([
            'class' => 'form-control noresize',
            'maxlength' => true,
            'data-length' => 20,
            'rows' => 10
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Enviar reporte'), ['class' => 'btn btn-tradegame btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
