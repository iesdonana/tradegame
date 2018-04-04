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
            data: {usuario: $(this).find('.usuario').text().trim()},
            success: function (data) {
                $('.mensajes').html(data);
                $('.nav-pills').find('li.active').find('.badge').remove();
                $('.nav-pills').find('li').removeClass('active');
                a.parent().addClass('active');
                $('#mensajes-receptor_id').val(a.data('id'));
                $('.scrollable').scrollTop($('.scrollable')[0].scrollHeight);

            }
        });
    });

JS;
$this->registerJs($js);
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default conversaciones">
    <?php foreach ($conversaciones as $conver): ?>
        <?php $emisor = $conver->emisor ?>
        <?php $usuario = ($emisor == null) ? $conver->receptor : $emisor ?>
        <li>
            <a href="" data-id="<?= $usuario->id ?>">
                <?= Html::img($usuario->usuariosDatos->avatar, [
                    'class' => 'img-circle img-chat'
                    ]) ?>
                    <span class='usuario'>
                        <?= Html::encode($usuario->usuario) ?>
                    </span>
                <?php if (($noLeidos = $usuario->noLeidos) > 0): ?>
                    <span class="badge"><?= $noLeidos ?></span>
                <?php endif; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
