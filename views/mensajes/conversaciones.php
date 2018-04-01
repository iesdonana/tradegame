<?php
use yii\helpers\Url;
use yii\helpers\Html;

$url = Url::to(['mensajes/conversacion']);
$js = <<<JS
    $('.nav-pills a').on('click', function(e) {
        e.preventDefault();
        var li = $(this).parent();
        $.ajax({
            url: '$url',
            data: {usuario: $(this).text().trim()},
            success: function (data) {
                $('.mensajes').html(data);
                $('.nav-pills').find('li').removeClass('active');
                li.addClass('active');
            }
        });
    });

JS;
$this->registerJs($js);
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default">
    <?php foreach ($conversaciones as $conver): ?>
        <li>
            <a href="">
                <?= Html::img($conver->emisor->usuariosDatos->avatar, [
                    'class' => 'img-circle img-chat'
                    ]) ?>
                <?= Html::encode($conver->emisor->usuario) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
