<?php
use app\models\Usuarios;

use yii\helpers\Url;
use yii\helpers\Html;

$url = Url::to(['mensajes/conversacion']);
$js = <<<JS
    $('.nav-pills a').on('click', function(e) {
        e.preventDefault();
        var a = $(this);
        $.ajax({
            url: '$url',
            data: {id: $(this).data('id')},
            success: function (data) {
                $('.mensajes').html(data);
                $('.nav-pills').find('li.active').find('.badge').remove();
                $('.nav-pills').find('li').removeClass('active');
                a.parent().addClass('active');
                $('#mensajes-receptor_id').val(a.data('id'));
                $('.scrollable').scrollTop($('.scrollable')[0].scrollHeight);
                $('#mensajes-contenido').prop('disabled', false);
                $('.nuevo-mensaje').removeClass('hidden');
                if (a.data('id') === 1) {
                    $('.nuevo-mensaje').addClass('hidden');
                }
            }
        });
    });

JS;
$this->registerJs($js);
?>

<ul class="nav nav-pills nav-stacked nav-email shadow mb-20 panel panel-default conversaciones">
    <?php foreach ($conversaciones as $conver): ?>
        <?php
        $emisor = $conver->emisor;
        $id = isset($conver->emisor_id) ? $conver->emisor_id : $conver->receptor_id;
        $usuario = ($emisor == null) ? $conver->receptor : $emisor;
        if ($usuario === null) {
            $usuario = new Usuarios([
                'id' => $id,
                'usuario' => Yii::t('app', 'Desconocido') . ' #' . $id
            ]);
        }
        $img = '@web/uploads/avatares/default.png';
        if (($datos = $usuario->usuariosDatos) !== null) {
            $img = $datos->avatar;
        }
        ?>

        <li>
            <a href="" data-id="<?= $usuario->id ?>">
                <?= Html::img($img, [
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
