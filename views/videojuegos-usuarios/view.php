<?php
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;


$css = <<<CSS
.datos-videojuego {
    margin: 10px;
}

.borrar {
    margin-left: 10px;
}

#date {
    margin: 20px;
}

img.fotos-videojuego {
    width: 300px;
    opacity: 0.8;
}

img.fotos-videojuego:hover {
    opacity: 1;
}

.side-crop {
    max-height: 150px;
    overflow: hidden;
}
.side-crop img {
    max-height: initial;
}

#modal-foto .modal-footer {
    text-align: inherit;
}

.container-fotos {
    margin-top: 20px;
}

.zoom-landscape {
    max-height: 450px;
}

.zoom-portrait {
    max-width: 450px;
}

CSS;
$this->registerCss($css);

$js = <<<JS
$(function() {
    $('.popup-modal').click(function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show');
    });

    $('img.fotos-videojuego').click(function(e) {
        e.preventDefault();
        var copy = $(this).clone();
        copy.removeClass('fotos-videojuego');
        copy.width > copy.height ? copy.addClass('zoom-landscape') : copy.addClass('zoom-portrait');
        $('#imagen-modal').html(copy);
        if ($('.fotos-videojuego img.fotos-videojuego').length == 1) {
            $('.btn-siguiente').remove();
            $('.btn-anterior').remove();
        }
        $('#modal-foto').modal('show');
    });

    $('.btn-siguiente').on('click', function(e) {
        e.preventDefault();
        pasar($('#imagen-modal img').data('num-foto'), '>');
    });

    $('.btn-anterior').on('click', function(e) {
        e.preventDefault();
        pasar($('#imagen-modal img').data('num-foto'), '<');
    })
});

function pasar(current, paso) {
    var imgs = $('.fotos-videojuego img.fotos-videojuego');
    var numImagenes = imgs.length;

    var prox = current + 1;
    if (paso == '<') {
        prox = current - 1;
    }

    if (prox > numImagenes) {
        prox = 1;
    } else if (prox < 1) {
        prox = numImagenes;
    }

    var copia = imgs.filter('[data-num-foto=' + prox + ']').clone();
    copia.removeClass('fotos-videojuego img-thumbnail');
    if (copia.width > copia.height) {
        copia.addClass('zoom-landscape')
    } else {
        copia.addClass('zoom-portrait')
    }
    $('#imagen-modal').html(copia)
}
JS;
$this->registerJs($js);

$user = $model->usuario->usuario;
$videojuego = $model->videojuego;
$this->title = $videojuego->nombre;
if ($model->borrado)  {
    $this->title = 'Publicación eliminada';
}

$this->params['breadcrumbs'][] = [
    'label' => Html::encode($user),
    'url' => ['usuarios/perfil', 'usuario' => $user]
];

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Publicaciones'),
    'url' => ['videojuegos-usuarios/publicaciones', 'usuario' => $user]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="panel panel-default panel-trade">
            <div class="panel-body">
                <?php if (!$model->borrado): ?>
                    <div id='date' class="row">
                        <div class="col-md-6 text-left">
                            <?= Utiles::FA('user') . ' ' . Yii::t('app', 'Publicado por') ?>
                            <?= Html::a($user, ['usuarios/perfil', 'usuario' => $user]) ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if (!$model->visible): ?>
                                <?= Html::tag('span', Utiles::FA('check') .
                                ' ' . Yii::t('app', 'Este videojuego ya ha sido intercambiado.'),
                                ['class' => 'text-success']) ?><br>
                            <?php endif; ?>
                            <?= Utiles::FA('clock', ['class' => 'far']) . ' ' . Yii::$app->formatter->asRelativeTime($model->created_at) ?>
                            <?php if ($model->visible && (Yii::$app->user->id === $model->usuario_id)): ?>
                                <?= Html::a(Utiles::FA('trash-alt'), null, ['class' => 'btn btn-xs btn-danger popup-modal']) ?>
                            <?php endif ?>
                        </div>
                    </div>
                    <?= $this->render('/videojuegos/datos', [
                        'model' => $videojuego
                    ]) ?>
                    <div class="datos-videojuego">
                        <strong><?= Yii::t('app', 'Comentarios del usuario') ?>: </strong><br>
                        <?php if (trim($model->mensaje) != ''): ?>
                            <?= Html::encode(Utiles::translate($model->mensaje)) ?>
                        <?php else: ?>
                            <?= Html::tag('em', Yii::t('app', 'No se ha proporcionado ningún comentario')) ?>
                        <?php endif ?>
                    </div>
                    <?php $imagenes = $model->getFotos() ?>
                    <?php if (count($imagenes) > 0): ?>
                        <?php $col = 12 / count($imagenes) ?>
                        <div class="fotos-videojuego datos-videojuego">
                            <strong><?= Yii::t('app', 'Fotos del videojuego') ?>: </strong><br>
                            <div class="row">
                                <div class="col-md-12 container-fotos">
                                    <?php $cont = 0 ?>
                                    <?php foreach ($imagenes as $imagen): ?>
                                        <div class="side-crop col-md-<?= $col ?> text-center">
                                            <?= Html::img('@web' . '/' . $imagen, [
                                                'class' => 'fotos-videojuego',
                                                'data-num-foto' => ++$cont
                                            ]) ?>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (Yii::$app->user->id !== $model->usuario_id && $model->visible): ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <?= Html::a(Yii::t('app', 'Hacer oferta') . ' ' . Utiles::FA('handshake', ['class' => 'far']), [
                                    'ofertas/create',
                                    'publicacion' => $model->id
                                ], ['class' => 'btn btn-lg btn-warning btn-block']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="row text-center">
                        <h3 class="text-danger"><?= Yii::t('app' , 'Esta publicación ya no existe') . ' ' . Utiles::FA('frown', ['class' => 'far']) ?></h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
    'header' => '<h3 class="modal-title">' . Yii::t('app', 'Foto del videojuego') . '</h3>',
    'id' => 'modal-foto',
    'footer' => '<div class="col-md-6">' .
        Html::a('Anterior', null, ['class' => 'btn btn-default pull-left btn-anterior']) .
        '</div>' .
        '<div class="col-md-6">' .
            Html::a('Siguiente', null, ['class' => 'btn btn-default pull-right btn-siguiente']) .
            '</div>'
]) ?>
<div id="imagen-modal" class="text-center">

</div>
<?php Modal::end() ?>
<?php Modal::begin([
 'header' => '<h2 class="modal-title">' . Yii::t('app', 'Borrar publicación') . '</h2>',
 'id'     => 'modal-delete',
 'footer' => Html::beginForm(['/videojuegos-usuarios/remove'], 'post') .
            Html::hiddenInput('id', $model->id) .
             Html::submitButton(
                 Utiles::FA('trash-alt') . ' ' . Yii::t('app', 'Borrar publicación'),
                 ['class' => 'btn btn-danger']
             )
             . Html::endForm()
 ])
 ?>
 <p><?= Yii::t('app', '¿Estás seguro de que deseas borrar permanentemente la publicación?') ?></p>

 <p class="text-warning"><?= Utiles::FA('exclamation-triangle') ?> <?= Yii::t('app', 'No volverá a recibir ofertas por este videojuego') ?></p>
 <?php Modal::end(); ?>
