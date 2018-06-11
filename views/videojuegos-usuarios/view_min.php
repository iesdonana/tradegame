<?php
use app\helpers\Utiles;

use yii\helpers\Html;

$this->registerCssFile('@web/css/listado_videojuegos.css');

// Busqueda se pasará a 'true' si viene para buscar videojuegos (el modelo sera de Videojuegos
// y no de VideojuegosUsuarios)
$esVideojuego = isset($busqueda);

$videojuego = $esVideojuego ? $model : $model->videojuego;

// Controles para mostrar la imagen de carátula en un tamaño mayor, o menor
// dependiendo del lugar donde rendericemos la vista en la aplicación
$valor = 2;
$clase = 'caratula-mini';
if (isset($big) && $big === true) {
    $valor = 4;
    $clase = 'caratula-big';
}
?>
<div class="row">
    <div class="col-md-12 videojuego-item"  itemscope itemtype="http://schema.org/VideoGame">
        <div class="row">
            <div class="col-md-<?= $valor ?>">
                <div class="row">
                    <?php $caratula = $videojuego->caratula ?>
                    <?php if (!$esVideojuego): ?>
                        <?= Html::a(Html::img($caratula, ['class' => $clase . ' center-block img-responsive']),
                        ['videojuegos-usuarios/ver', 'id' => $model->id]) ?>

                        <div class="row botones-acciones">
                            <div class="col-md-12">
                                <?php if ($model->usuario_id !== Yii::$app->user->id): ?>
                                    <?= Html::a('<strong>' . Utiles::FA('handshake', ['class' => 'far']) . ' ' .
                                        Yii::t('app', 'Hacer oferta') . '</strong>', [
                                        'ofertas/create',
                                        'publicacion' => $model->id
                                    ], ['class' => 'btn btn-xs btn-block btn-warning btn-offer center-block ' . $clase]) ?>
                                <?php endif ?>
                                <?= Html::a('<strong>' . Utiles::FA('info-circle') . ' ' .
                                Yii::t('app', 'Ficha técnica') . '</strong>', [
                                    'videojuegos/ver',
                                    'id' => $videojuego->id
                                ], ['class' => 'btn btn-xs btn-block btn-primary center-block ' . $clase]) ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <?= Html::a(Html::img($caratula, ['class' => $clase . ' center-block img-responsive']),
                        ['videojuegos/ver', 'id' => $videojuego->id]) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-<?= 12 - $valor ?>">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-8 cabecera-videojuego">
                            <strong class='titulo text-tradegame' itemprop="name">
                                <?php if ($esVideojuego): ?>
                                    <?= Html::a(Html::encode($videojuego->nombre),
                                    ['videojuegos/ver', 'id' => $videojuego->id]) ?>
                                <?php else: ?>
                                    <?= Html::a(Html::encode($videojuego->nombre),
                                    ['videojuegos-usuarios/ver', 'id' => $model->id]) ?>
                                <?php endif; ?>
                            </strong><br>
                            <span itemprop="gamePlatform"><?= Utiles::badgePlataforma($videojuego->plataforma->nombre) ?></span>
                            <span class="label label-default"><?= Yii::t('app', $videojuego->genero->nombre) ?></span> <br>
                        </div>
                        <?php if (!$esVideojuego): ?>
                        <div class="col-md-4">
                            <div class='text-right date-publicado text-center'>
                                <?= Utiles::FA('clock', ['class' => 'far']) . ' ' .
                                Yii::$app->formatter->asRelativeTime($model->created_at) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!$esVideojuego): ?>
                        <div class="row">
                            <div class="col-md-12 published-by">
                                <?php $user = $model->usuario->usuario ?>
                                <?= Utiles::FA('user') . ' ' . Yii::t('app', 'Publicado por') ?>
                                <?= Html::a($user, ['usuarios/perfil', 'usuario' => $user]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div>

                        <br>
                        <strong><?= Yii::t('app', 'Descripción') ?>:</strong>
                        <em itemprop="about"><?= Html::encode(Utiles::translate($videojuego->descripcion)) ?></em>
                        <?php if (!$esVideojuego): ?>
                            <hr class='divide'>
                            <strong><?= Yii::t('app', 'Comentarios') ?>:</strong>
                            <div class="comentarios-videojuego">
                                <?php if (trim($model->mensaje) != ''): ?>
                                    <?= Html::encode(Utiles::translate($model->mensaje)) ?>
                                <?php else: ?>
                                    <?= Html::tag('em', Yii::t('app', 'No se ha proporcionado ningún comentario')) ?>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
