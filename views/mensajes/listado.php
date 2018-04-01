<?php
use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\widgets\ActiveForm;


$url = Url::to(['mensajes/conversacion']);
$js = <<<JS
function peticion(usuario) {
    $.ajax({
        url: '$url',
        data: {usuario: usuario},
        success: function (data) {
            $('.mensajes').html(data);
            $('.nav-pills').find('li').first().addClass('active');
            $('#mensajes-receptor_id').val($('.nav-pills').find('li').first().find('a').data('id'));
        }
    });
}
peticion($('.nav-pills').find('li').first().find('a').text().trim());

JS;
$this->registerJs($js);

$create = Url::to(['mensajes/create']);
$url = Url::to(['mensajes/conversacion']);

$js = <<<JS
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
// $('.pre-scrollable').scrollTop($('.pre-scrollable')[0].scrollHeight);

$('#form-mensaje').on('click', 'button', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    $.ajax({
        url: '$create',
        data: form.serialize(),
        type: 'POST',
        success: function(datos) {
            var modelo = datos;
            $.ajax({
                url: '$url',
                data: {usuario: $('.nav-pills li.active').find('a').text().trim()},
                success: function (content) {
                    $('.mensajes').html(content);
                    $('.nav-pills').find('a[data-id=' + datos.receptor_id + ']').parent().addClass('active');
                    $('#mensajes-receptor_id').val(datos.receptor_id);
                    $('.pre-scrollable').scrollTop($('.pre-scrollable')[0].scrollHeight);
                    $('#mensajes-contenido').val('');
                }
            });
        }
    });
})
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php if (count($conversaciones) > 0): ?>
<div class="row">
        <div class="col-md-3">
            <h4>Conversaciones</h4>
            <?= $this->render('conversaciones', [
                'conversaciones' => $conversaciones
                ]) ?>
            </div>
            <div class="col-md-9">
                <h4>Mensajes</h4>
                <div class="mensajes">

                </div>

                <div class="panel panel-defautl">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'form-mensaje'
                        ]); ?>
                        <div class="col-md-10">
                            <?= $form->field($model, 'receptor_id')->hiddenInput(['value' => ''])->label(false) ?>
                            <?= $form->field($model, 'contenido')->textarea([
                                'maxlength' => true,
                                'placeholder' => 'Mensaje'
                            ])
                            ->label(false) ?>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= Html::submitButton('Enviar', ['class' => 'btn btn-tradegame']) ?>
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
                    <h3>Aún no has intercambiado mensajes con ningún usuario <?= Utiles::FA('frown', ['class' => 'far']) ?></h3>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
