<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model app\models\Valoraciones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="valoraciones-form">

    <?php $form = ActiveForm::begin(['options' => [
        'class' => 'cargaForm'
    ]]); ?>

    <?= $form->field($model, 'comentario')->textarea(['maxlength' => true, 'rows' => 7]) ?>

    <?= $form->field($model, 'num_estrellas')->widget(StarRating::className(), [
        'pluginOptions' => ['step' => 1]
    ]) ?>

    <div class="form-group col-md-offset-2 col-md-8">
        <?= Html::submitButton(Yii::t('app', 'Valorar') . ' ', ['class' => 'btn btn-tradegame btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
