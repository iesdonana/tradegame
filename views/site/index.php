<?php
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

$this->title = 'TradeGame';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        Top usuarios valorados
                    </div>
                </div>
                <div class="panel-body">
                    <h3>Próximamente...</h3>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        Últimos videojuegos publicados
                    </div>
                </div>
                <div class="panel-body">
                    <h3>Próximamente...</h3>
                </div>
            </div>
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->usuariosDatos->geoloc !== null) : ?>
            <div class="map panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        Usuarios cercanos <?= Utiles::FA('map-marker') ?>
                    </div>
                </div>
                <div class="panel-body">
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
        <?php endif ?>
        </div>
    </div>
</div>
