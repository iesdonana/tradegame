<?php
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;


$css = <<<CSS
.datos-videojuego {
    margin: 10px;
}

.comentarios-videojuego:empty:after {
    font-style: italic;
    content: 'No se ha proporcionado ningún comentario';
}

.borrar {
    margin-left: 10px;
}

#date {
    margin: 20px;
}
CSS;
$this->registerCss($css);


$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            $('#modal-delete').modal('show');
        });
    });"
);

$user = $model->usuario->usuario;
$videojuego = $model->videojuego;
$this->title = $videojuego->nombre;
if ($model->borrado)  {
    $this->title = 'Publicación eliminada';
}

$label = 'Publicaciones';

$this->params['breadcrumbs'][] = [
    'label' => Html::encode($user),
    'url' => ['usuarios/perfil', 'usuario' => $user]
];

$this->params['breadcrumbs'][] = [
    'label' => $label,
    'url' => ['videojuegos-usuarios/publicaciones', 'usuario' => $user]
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if (!$model->borrado): ?>
                    <div id='date'>
                        <div class="col-md-6 text-left">
                            <?= Utiles::FA('user') ?> Publicado por
                            <?= Html::a($user, ['usuarios/perfil', 'usuario' => $user]) ?>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if (!$model->visible): ?>
                                <?= Html::tag('span', Utiles::FA('check') .
                                ' Esta videojuego ya ha sido intercambiado.',
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
                        <strong>Comentarios del usuario: </strong><br>
                        <div class="comentarios-videojuego"><?= Html::encode($model->mensaje) ?></div>
                    </div>
                    <?php if (Yii::$app->user->id !== $model->usuario_id && $model->visible): ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <?= Html::a('Hacer oferta ' . Utiles::FA('handshake', ['class' => 'far']), [
                                    'ofertas/create',
                                    'publicacion' => $model->id
                                ], ['class' => 'btn btn-lg btn-warning btn-block']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="row text-center">
                        <h3 class="text-danger">Esta publicación ya no existe <?= Utiles::FA('frown', ['class' => 'far']) ?></h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
 'header' => '<h2 class="modal-title">Borrar publicación</h2>',
 'id'     => 'modal-delete',
 'footer' => Html::beginForm(['/videojuegos-usuarios/remove'], 'post') .
            Html::hiddenInput('id', $model->id) .
             Html::submitButton(
                 Utiles::FA('trash-alt') . ' Borrar publicación',
                 ['class' => 'btn btn-danger']
             )
             . Html::endForm()
 ])
 ?>
 <p>¿Estás seguro de que deseas borrar permanentemente la publicación?</p>

 <p class="text-warning"><?= Utiles::FA('exclamation-triangle') ?> No volverá a recibir ofertas por este videojuego</p>
 <?php Modal::end(); ?>
