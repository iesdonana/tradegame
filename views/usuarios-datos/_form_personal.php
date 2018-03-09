<?php

use app\models\UsuariosGeneros;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\file\FileInput;

use kartik\datecontrol\DateControl;


/* @var $this yii\web\View */
/* @var $model app\models\UsuariosDatos */
/* @var $form yii\widgets\ActiveForm */

$js = <<<EOT
function cargarImagen(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#img-edit').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#usuariosdatos-foto").change(function() {
  cargarImagen(this);
});
EOT;
$this->registerJs($js);
?>

<div class="usuarios-datos-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <?= Html::img($model->avatar, [
                'id' => 'img-edit',
                'class' => 'center-block'
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                'pluginOptions' => [
                    'showUpload' => false,
                    'showPreview' => false,
                    'showCaption' => false,
                    'showRemove' => false,
                    'browseClass' => 'btn btn-tradegame btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'browseLabel' =>  'Sube tu avatar'
                ],
                'options' => ['accept' => 'image/*'],
                ])->label(false);?>
            </div>
    </div>


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

    <?= $form->field($model, 'fecha_nacimiento')->widget(DateControl::classname(), [
        'readonly' => true,
        'widgetOptions' => [
            'layout' => '{picker}{input}{remove}',
            'pluginOptions' => [
                'autoclose' => true,
            ]
        ]
    ])->label(false) ?>

    <?= $form->field($model, 'genero_id', [
        'template' => Utiles::inputGlyphicon('user')
        ])->dropDownList(
            UsuariosGeneros::find()
            ->select('sexo')
            ->indexBy('id')
            ->column(), ['prompt' => 'Selecciona un género'])
    ?>

    <div class="col-md-offset-2 col-md-8">
        <div class="form-group">
            <?= Html::submitButton('Modificar', ['class' => 'btn btn-tradegame btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
