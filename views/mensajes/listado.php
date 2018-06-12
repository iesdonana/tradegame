<?php
use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Mis mensajes');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/chat.css');
$urlConver = Url::to(['mensajes/conversacion']);
$create = Url::to(['mensajes/create']);
$urlNuevos = Url::to(['mensajes/mensajes-nuevos']);
$js = <<<JS
var primero = $('.nav-pills').find('li').first();
 $('#mensajes-receptor_id').val(primero.find('a').data('id'));
$('.cargaForm button').prop('disabled', true);
$('.nav-pills').find('li').first().addClass('active');
peticionConversacion('$urlConver', '$urlNuevos', primero.find('a').data('id'));

setInterval(function(){
    peticionConversacion('$urlConver', '$urlNuevos', $('.nav-pills').find('li.active').find('a').data('id'), true);
}, 5000);

$('#form-mensaje').on('click', 'button', function(e) {
    e.preventDefault();
    $('.cargaForm').trigger('beforeSubmit');
    var form = $(this).closest('form');
    $.ajax({
        url: '$create',
        data: form.serialize(),
        type: 'POST',
        success: function(datos) {
            peticionConversacion('$urlConver', '$urlNuevos', datos.receptor_id);
            var btn = $('.cargaForm').find('button');
            btn.prop('disabled', false);
            btn.find('svg').remove();
        }
    });
})
JS;
$this->registerJs($js);

$this->registerJsFile('@web/js/chat.js', ['position' => View::POS_HEAD]);
?>
<?php if (count($conversaciones) > 0): ?>
<div class="row alto">
        <div class="col-md-3">
            <h4><?= Yii::t('app', 'Conversaciones') ?> <?= Html::a(Utiles::FA('plus-circle'), [
                'mensajes/nuevo'
            ], ['class' => 'btn btn-xs btn-tradegame']) ?></h4>
            <?= $this->render('conversaciones', [
                'conversaciones' => $conversaciones
                ]) ?>
            </div>
            <div class="col-md-9">
                <h4><?= Yii::t('app', 'Mensajes') ?></h4>
                <div class="conversacion">
                    <div class="panel panel-default scrollable">
                        <div class="panel-body mensajes">

                        </div>
                    </div>
                </div>
                <div class="msg-hidden">

                </div>
                <div class="panel panel-default nuevo-mensaje">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'form-mensaje',
                            'options' => [
                                'class' => 'cargaForm'
                            ]
                        ]); ?>
                        <div class="col-md-10">
                            <?= $form->field($model, 'receptor_id')->hiddenInput(['value' => ''])->label(false) ?>
                            <?= $form->field($model, 'contenido')->textarea([
                                'class' => 'form-control noresize',
                                'maxlength' => true,
                                'placeholder' => Yii::t('app', 'Mensaje'),
                            ])
                            ->label(false) ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('app', 'Enviar') . ' ', ['class' => 'btn btn-tradegame btn-block']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
</div>
<?php else: ?>
    <div class="row text-center">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 text-center txt-empty">
                            <?= Yii::t('app', 'Aún no has intercambiado mensajes con ningún usuario') . ' ' . Utiles::FA('frown', ['class' => 'far']) ?>
                        </div>
                        <div class="col-md-12 text-center btn-enviar">
                            <?= Html::a('Enviar mensaje', ['mensajes/nuevo'], ['class' => 'btn btn-tradegame']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
