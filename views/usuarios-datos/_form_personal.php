<?php

use app\models\UsuariosGeneros;

use app\helpers\Utiles;

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\file\FileInput;

use kartik\datecontrol\DateControl;

use dosamigos\google\maps\MapAsset;

MapAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\UsuariosDatos */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
function cargarImagen(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
        $('#img-edit').siblings('a').remove();
        $('#img-edit').attr('src', e.target.result);
        var a = $('<a>');
        a.addClass('badge-corner');
        a.addClass('badge-corner-base');
        a.attr('title', 'Avatar pendiente de subida');
        var span = $('<span></span>');
        span.addClass('glyphicon glyphicon-cloud-upload');
        a.append(span);
        $('#img-edit').parent().append(a);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#usuariosdatos-foto").change(function() {
    cargarImagen(this);
});

$('.cargaForm button').on('click', function(e) {
    e.preventDefault();
    var address = $('#usuariosdatos-localidad').val() + ' ' + $('#usuariosdatos-direccion').val();
    var geocoder = new google.maps.Geocoder();
    // Cuando complete la petición asíncrona, enviamos el formulario
    var resul = '';
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            resul = results[0].geometry.location
            resul = resul.lat() + ',' + resul.lng();
        }
        $('#usuariosdatos-geoloc').val(resul);
        console.log($('.cargaForm').serialize());
        $('.cargaForm').submit();
    });
});
JS;
$this->registerJs($js);
$this->registerCssFile('@web/css/badge.css');
?>

<div class="usuarios-datos-form">
    <?php $form = ActiveForm::begin(['options' => [
        'class' => 'cargaForm'
    ]]); ?>
    <div class="col-md-3">
        <div class="row">
            <?= Html::img($model->avatar, [
                'id' => 'img-edit',
                'class' => 'center-block relative'
                ]) ?>
        </div>
        <div class="row">
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
    <div id="col-datos-personales" class="col-md-9">

        <?= $form->field($model, 'nombre_real', [
            'template' => Utiles::inputTemplate('tag', Utiles::GLYPHICON)
            ])->textInput(['maxlength' => true, 'placeholder' => 'Nombre real']) ?>

        <?= $form->field($model, 'localidad', [
            'template' => Utiles::inputTemplate('globe', Utiles::GLYPHICON)
            ])->textInput(['maxlength' => true, 'placeholder' => 'Localidad']) ?>

        <?= $form->field($model, 'direccion', [
            'template' => Utiles::inputTemplate('screenshot', Utiles::GLYPHICON)
            ])->textInput(['maxlength' => true, 'placeholder' => 'Dirección']) ?>

        <?= $form->field($model, 'geoloc')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'provincia', [
            'template' => Utiles::inputTemplate('map-marker', Utiles::GLYPHICON)
            ])->textInput(['maxlength' => true, 'placeholder' => 'Provincia']) ?>

        <?= $form->field($model, 'telefono', [
            'template' => Utiles::inputTemplate('earphone', Utiles::GLYPHICON)
            ])->textInput(['maxlength' => true, 'placeholder' => 'Teléfono']) ?>

        <?= $form->field($model, 'biografia', [
            'template' => Utiles::inputTemplate('book', Utiles::GLYPHICON)
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
            'template' => Utiles::inputTemplate('user', Utiles::GLYPHICON)
            ])->dropDownList(
                UsuariosGeneros::find()
                ->select('sexo')
                ->indexBy('id')
                ->column(), ['prompt' => 'Selecciona un género'])
                ?>

        </div>
        <div class="row">
            <div class="col-md-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="form-group">
                    <?= Html::submitButton('Modificar ', ['class' => 'btn btn-tradegame btn-block']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>


</div>
