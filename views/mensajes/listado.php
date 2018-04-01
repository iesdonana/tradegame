<?php
use yii\helpers\Url;


$url = Url::to(['mensajes/conversacion']);
$js = <<<JS
    $.ajax({
        url: '$url',
        data: {usuario: $('.nav-pills').find('li').first().find('a').text().trim()},
        success: function (data) {
            $('.mensajes').html(data);
            $('.nav-pills').find('li').first().addClass('active');
        }
    });
JS;
$this->registerJs($js);
?>

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
    </div>
</div>
