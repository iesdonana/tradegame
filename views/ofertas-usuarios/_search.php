<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OfertasUsuariosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ofertas-usuarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'publicado') ?>

    <?= $form->field($model, 'ofrecido') ?>

    <?= $form->field($model, 'usuario_publicado') ?>

    <?= $form->field($model, 'usuario_ofrecido') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
