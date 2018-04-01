<?php
use yii\helpers\Url;
use yii\helpers\Html;

$url = Url::to(['mensajes/conversacion']);
$js = <<<JS
    $('.nav-pills a').on('click', function(e) {
        e.preventDefault();
        var a = $(this);
        $.ajax({
            url: '$url',
            data: {usuario: $(this).text().trim()},
            success: function (data) {
                $('.mensajes').html(data);
                $('.nav-pills').find('li').removeClass('active');
                a.parent().addClass('active');
                $('#mensajes-receptor_id').val(a.data('id'));
                $('.pre-scrollable').scrollTop($('.pre-scrollable')[0].scrollHeight);
            }
        });
    });

JS;
$this->registerJs($js);
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default">
    <?php foreach ($conversaciones as $conver): ?>
        <?php $emisor = $conver->emisor ?>
        <li>
            <a href="" data-id="<?= $emisor->id ?>">
                <?= Html::img($emisor->usuariosDatos->avatar, [
                    'class' => 'img-circle img-chat'
                    ]) ?>
                <?= Html::encode($conver->emisor->usuario) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
