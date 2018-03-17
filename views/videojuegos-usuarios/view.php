<?php
use app\helpers\Utiles;

use yii\helpers\Html;

$this->registerCssFile('@web/css/listado_videojuegos.css');

// Busqueda se pasará a 'true' si viene para buscar videojuegos, y por lo tanto,
// solo queremos que muestre datos del videojuego, y no de videojuegos_usuarios
if (isset($busqueda)) {
    $videojuego = $model;
} else {
    $videojuego = $model->videojuego;
}

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
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-<?= $valor ?>">
                <div class="row">
                    <?= Html::img($videojuego->caratula, ['class' => $clase . ' img-thumbnail center-block']) ?>
                </div>
                <div class="row text-center">
                    <?= Html::a(Utiles::FA('info-circle') . ' Ficha completa',
                        ['videojuegos/ver', 'id' => $videojuego->id],
                        ['class' => 'btn btn-xs btn-primary']) ?>
                </div>
            </div>
            <div class="col-md-<?= 12 - $valor ?>">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10 no-padding">
                            <strong class='titulo text-tradegame'><?= $videojuego->nombre ?></strong><br>
                            <?= Utiles::badgePlataforma($videojuego->plataforma->nombre) ?> <br>
                        </div>
                        <?php if (!isset($busqueda)): ?>
                            <div class="row">
                                <div class='text-center date-publicado'>
                                    <?= Utiles::FA('clock', ['class' => 'far']) . ' ' .
                                    Yii::$app->formatter->asRelativeTime($model->created_at) ?>
                                    <?php if ($model->usuario_id !== Yii::$app->user->id): ?>
                                        <?= Html::a('<strong>Hacer oferta</strong>', [
                                            'ofertas/create',
                                            'publicacion' => $model->id
                                        ], ['class' => 'btn btn-sm btn-warning']) ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <strong>Descripción:</strong>
                        <em><?= $videojuego->descripcion ?></em>
                        <?php if (!isset($busqueda) && $model->mensaje !== ''): ?>
                            <hr class='divide'>
                            <strong>Comentarios:</strong>
                            <?= $model->mensaje ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
