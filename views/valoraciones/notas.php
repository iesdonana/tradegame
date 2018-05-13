<?php

use app\helpers\Utiles;

use yii\helpers\Url;
use yii\helpers\Html;

use yii\bootstrap\Modal;

/* $num int Número de valoraciones que se mostrarán */
$cont = isset($num) ? $num : count($valRecibidas);

$url = Url::to(['valoraciones/buscar']);
$this->registerJs("
    $(function() {
        $('.sticky span').click(function(e) {
            e.preventDefault();
            var usuario = $(this).find('h2').text();
            var estrellas = $(this).find('.estrellas').html();
            var cuerpo = $('#sticky-modal').find('p');
            cuerpo.text($(this).children('.comentario').data('full'));
            cuerpo.html(cuerpo.text() + '<br><br>' + estrellas);
            $('#sticky-modal').find('.modal-title').text(usuario);
            $('#sticky-modal').modal('show');
        });
    });"
);
$this->registerCssFile('@web/css/sticky.css');
?>

<?php for ($i = 0;  $i < $cont; $i++): ?>
    <?php $val = $valRecibidas[$i] ?>
    <li>
        <span data-id="<?= $val->id ?>">
            <h2><?= Html::encode($val->usuarioValora->usuario) ?></h2>
            <?php $in = Utiles::translate($val->comentario) ?>
            <p class="comentario" data-full="<?= $in ?>">
                <?php $out = strlen($in) > 100 ? substr($in,0,100)."..." : $in; ?>
                <?= Html::encode($out) ?><br>
                <div class="estrellas">
                    <?= Utiles::pintarEstrellas($val->num_estrellas) ?>
                </div>
            </p>
        </span>
    </li>
<?php endfor; ?>

<?php Modal::begin([
 'header' => '<h2 class="modal-title">Usuario</h2>',
 'id'     => 'sticky-modal',
 'size' => 'modal-sm'
 ])
 ?>
 <p>Valoracion</p>
 <?php Modal::end(); ?>
