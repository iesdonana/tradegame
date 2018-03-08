<?php

use app\models\UsuariosGeneros;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuariosDatos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuarios-datos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_real', [
        'template' => Utiles::inputGlyphicon('tag')
        ])->textInput(['maxlength' => true, 'placeholder' => 'Nombre real']) ?>

    <?= $form->field($model, 'localidad', [
        'template' => Utiles::inputGlyphicon('globe')
        ])->textInput(['maxlength' => true, 'placeholder' => 'Localidad']) ?>

    <?= $form->field($model, 'provincia', [
        'template' => Utiles::inputGlyphicon('map-marker')
        ])->textInput(['maxlength' => true, 'placeholder' => 'Provincia']) ?>

    <?= $form->field($model, 'telefono', [
        'template' => Utiles::inputGlyphicon('earphone')
        ])->textInput(['maxlength' => true, 'placeholder' => 'Teléfono']) ?>

    <?= $form->field($model, 'biografia', [
        'template' => Utiles::inputGlyphicon('book')
        ])->textarea(['maxlength' => true, 'placeholder' => 'Biografía']) ?>

    <?= $form->field($model, 'fecha_nacimiento', [
        'template' => Utiles::inputGlyphicon('calendar')
        ])->textInput(['maxlength' => true, 'placeholder' => 'Fecha de nacimiento']) ?>

    <?= $form->field($model, 'genero_id', [
        'template' => Utiles::inputGlyphicon('user')
        ])->dropDownList(
            UsuariosGeneros::find()
            ->select('sexo')
            ->indexBy('id')
            ->column())
    ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton('Modificar', ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
