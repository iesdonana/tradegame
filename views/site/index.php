<?php
use app\assets\BxAsset;

use app\models\UsuariosDatos;

use app\helpers\Utiles;

use yii\helpers\Html;

use dosamigos\google\maps\Map;
use dosamigos\google\maps\Size;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\MapAsset;
use dosamigos\google\maps\LatLngBounds;

use dosamigos\google\maps\overlays\Icon;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\Animation;
use dosamigos\google\maps\overlays\InfoWindow;

/* @var $this yii\web\View */

MapAsset::register($this);
BxAsset::register($this);

$css = <<<CSS
.container.custom-container {
    margin: 0 auto;
    width: 100%;
}

.usuarios-cercanos {
    width: 100%;
    margin: 0 auto;
    padding: 0;
}

#gmap0-map-canvas {
    height: 300px !important;
}

.panel-sm .panel-heading {
    padding-top: 5px;
    padding-bottom: 5px;
}

.panel-sm .panel-heading .panel-title {
    font-size: 15px;
}

@media(max-width:767px) {
    .accesos-directos .btn-tradegame {
        margin-top: 10px;
    }
}
CSS;
$this->registerCss($css);
$js = <<<JS
$('.bxslider').removeClass('hidden');
$('.bxslider').bxSlider({auto: true, stopAutoOnClick: true});
JS;
$this->registerJs($js);
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default panel-trade panel-sm accesos-directos">
                <div class="panel-heading">
                    <div class="panel-title">
                        <?= Utiles::FA('external-link-alt') . ' ' . Yii::t('app', 'Accesos directos') ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-md-4">
                        <?= Html::a(Yii::t('app', 'Mis ofertas') . ' ' .
                        Utiles::FA('handshake', ['class' => 'far']),
                        ['ofertas-usuarios/index'],
                        ['class' => 'btn btn-tradegame btn-block']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= Html::a(Yii::t('app', 'Mis valoraciones') . ' ' .
                        Utiles::FA('star', ['class' => 'far']),
                        ['valoraciones/index'],
                        ['class' => 'btn btn-tradegame btn-block']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= Html::a(Yii::t('app', 'Mis mensajes') . ' ' .
                        Utiles::FA('inbox'),
                        ['mensajes/listado'],
                        ['class' => 'btn btn-tradegame btn-block']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default panel-trade panel-sm">
                <div class="panel-heading">
                    <div class="panel-title">
                        <?= Utiles::FA('bars') . ' ' . Yii::t('app', 'Últimas publicaciones') ?>
                    </div>
                </div>
                <div class="panel-body">
                    <ul class='bxslider hidden'>
                        <?php foreach ($lastVideojuegos as $videojuego): ?>
                            <li>
                                <div class="col-md-offset-1 col-md-10">
                                    <?= $this->render('/videojuegos-usuarios/view_min', [
                                        'model' => $videojuego,
                                        'big' => true
                                    ]) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->usuariosDatos->geoloc !== null) : ?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default panel-trade panel-sm">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= Utiles::FA('map-marker') . ' ' . Yii::t('app', 'Usuarios cercanos') ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="usuarios-cercanos">
                            <?php
                            $markers = [];
                            $me = Yii::$app->user->identity;
                            $meDatos = $me->usuariosDatos;
                            $icon = new Icon([
                                'scaledSize' => new Size(['height' => 50, 'width' => 50]),
                                'url' => $meDatos->avatar
                            ]);
                            $coord = new LatLng(['lat' => $meDatos->lat, 'lng' => $meDatos->lng]);
                            $marker = new Marker([
                                'position' => $coord,
                                'title' => 'Casa de ' . Html::encode($me->usuario),
                                'icon' => $icon,
                                'animation' => Animation::BOUNCE
                            ]);
                            $markers[] = $marker;

                            // Busca usuarios cercanos
                            $usuarios = UsuariosDatos::find()
                                ->where(['is not', 'geoloc', null])
                                ->andWhere(['!=', 'id_usuario', $meDatos->id_usuario])
                                ->all();
                            foreach ($usuarios as $usuario) {
                                if (Utiles::distancia($meDatos, $usuario) < 100) {
                                    $icon = new Icon([
                                        'scaledSize' => new Size(['height' => 50, 'width' => 50]),
                                        'url' => $usuario->avatar
                                    ]);
                                    $marker = new Marker([
                                        'position' => new LatLng(['lat' => $usuario->lat, 'lng' => $usuario->lng]),
                                        'icon' => $icon,
                                    ]);
                                    $nom = $usuario->usuario->usuario;
                                    $marker->attachInfoWindow(
                                        new InfoWindow([
                                            'content' => '<h4>' . Html::a($nom, ['usuarios/perfil', 'usuario' => $nom]) . '</h4>'
                                        ])
                                    );
                                    $markers[] = $marker;
                                }
                            }

                            $latlng = LatLngBounds::getBoundsOfMarkers($markers);
                            $map = new Map([
                                'center' => $latlng->getCenterCoordinates(),
                                'zoom' => $latlng->getZoom(300),
                                'width' => '100%',
                            ]);
                            foreach ($markers as $marker) {
                                $map->addOverlay($marker);
                            }
                            ?>

                            <?= $map->display() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
