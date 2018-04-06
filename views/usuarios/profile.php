<?php

/* @var $this yii\web\View */
use app\assets\BxAsset;

use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;

use dosamigos\google\maps\Map;
use dosamigos\google\maps\Size;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\LatLngBounds;

use dosamigos\google\maps\overlays\Icon;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\Animation;

/* @var $model app\models\Usuarios */


BxAsset::register($this);

$this->title = $model->usuario;
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            $('#modal-delete').modal('show');
        });
    });"
);
$this->registerCssFile('@web/css/profile.css');
$this->registerJs("$('.bxslider').bxSlider({auto: true, stopAutoOnClick: true});");
?>
<div class="col-md-12">
<div class="panel panel-default">
   <div class="panel-heading resume-heading">
      <div class="row">
         <div class="col-lg-12">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php $datos = $model->usuariosDatos; ?>
                    <div class="img-thumbnail">
                        <?= Html::img($datos->avatar, [
                            'id' => 'img-profile',
                            'class' => 'img-responsive'
                            ]) ?>
                    </div>
                </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
               <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Usuario:</strong>
                        <?= Html::encode($model->usuario) ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Email:</strong>
                        <?= Html::encode($model->email) ?>
                    </li>
                    <?php if ($datos->nombre_real): ?>
                        <li class="list-group-item">
                            <strong>Nombre:</strong>
                            <?= Html::encode($datos->nombre_real) ?>
                        </li>
                    <?php endif ?>
                    <?php if ($datos->localidad): ?>
                        <li class="list-group-item">
                            <strong>Localidad:</strong>
                            <?= Html::encode($datos->localidad) ?>
                        </li>
                    <?php endif ?>
                    <?php if ($datos->fecha_nacimiento): ?>
                        <li class="list-group-item">
                            <strong>Fecha de nacimiento:</strong>
                            <?= $datos->fecha_nacimiento ?>
                        </li>
                    <?php endif ?>
               </ul>
                <?php if ($model->id === Yii::$app->user->id): ?>
                    <?= Html::a(
                        Utiles::FA('edit') . ' Editar perfil',
                        ['usuarios/modificar', 'seccion' => 'personal'],
                        ['class' => 'btn btn-default']
                    ) ?>
                    <?= Html::a(
                        Utiles::FA('user-times') . ' Borrar cuenta',
                        ['usuarios/remove'],
                        ['class' => 'btn btn-danger popup-modal']
                    ) ?>
                <?php else: ?>
                    <?= Html::a(
                        'Enviar mensaje ' . Utiles::FA('comment', ['class' => 'far']),
                        [
                            'mensajes/nuevo',
                            'receptor' => $model->usuario
                        ],
                        [
                            'class' => 'btn btn-primary'
                        ])
                    ?>
                    <?= Html::a(
                        'Reportar ' . Utiles::FA('flag', ['class' => 'far']),
                        [
                            'reportes/create',
                            'usuario' => $model->usuario
                        ],
                        [
                            'class' => 'btn btn-danger'
                        ])
                    ?>
                <?php endif ?>
            </div>
         </div>
      </div>
   </div>
   <?php if (count($listado) > 0): ?>
       <div class="bs-callout bs-callout-danger">
           <h4>Últimos videojuegos publicados</h4>
           <ul class='bxslider'>
                <?php foreach ($listado as $vp): ?>
                    <li>
                        <div class="col-md-offset-1 col-md-10">
                            <?= $this->render('/videojuegos-usuarios/view_min', [
                                'model' => $vp,
                                'big' => true
                                ]) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?= Html::a('Ver todas las publicaciones [+]',
                ['/videojuegos-usuarios/publicaciones', 'usuario' => Yii::$app->request->get('usuario')],
                ['class' => 'btn btn-xs btn-tradegame pull-right']
            ) ?>
       </div>
    <?php endif ?>
    <div class="bs-callout bs-callout-danger">
       <h4>Biografía</h4>
       <p><?= ($datos->biografia) ? Html::encode($datos->biografia) :
       '<em>El usuario no ha facilitado una biografía</em>' ?></p>
    </div>
    <?php $valRecibidas = $model->valoraciones ?>
    <?php if (count($valRecibidas) > 0): ?>
        <div class="bs-callout bs-callout-danger sticky">
            <h4>Valoraciones</h4>
            <ul>
                <?php
                $params = [
                    'valRecibidas' => $valRecibidas,
                ];
                if (count($valRecibidas) >= 8) {
                    $params[] = [
                        'num' => 8
                    ];
                }
                ?>
                <?= $this->render('/valoraciones/notas', $params) ?>
            </ul>
            <?= Html::a('Ver todas las valoraciones [+]',
            ['/valoraciones/listado-notas', 'usuario' => Yii::$app->request->get('usuario')],
            ['class' => 'btn btn-xs btn-tradegame pull-right']
            ) ?>
        </div>
    <?php endif; ?>
    <?php if ($datos->geoloc !== null) : ?>
    <div class="bs-callout bs-callout-danger">
       <h4>Localización</h4>
       <?php
        $markers = [];
        $coord = new LatLng(['lat' => $datos->lat, 'lng' => $datos->lng]);

        $icon = new Icon([
            'scaledSize' => new Size(['height' => 50, 'width' => 50]),
            'url' => $datos->avatar
        ]);
        $marker = new Marker([
            'position' => $coord,
            'title' => 'Casa de ' . Html::encode($model->usuario),
            'icon' => $icon,
            'animation' => Animation::BOUNCE
        ]);
        $markers[] = $marker;

        $me = Yii::$app->user;
        if (!$me->isGuest &&
            $me->identity->usuariosDatos->geoloc !== null &&
            $me->id !== $model->id) {
            $me = $me->identity;
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
<?php endif ?>

   </div>
   <?php Modal::begin([
    'header' => '<h2 class="modal-title">Borrar cuenta</h2>',
    'id'     => 'modal-delete',
    'footer' => Html::beginForm(['/usuarios/remove'], 'post') .
                Html::submitButton(
                    Utiles::FA('trash-alt') . ' Borrar definitivamente',
                    ['class' => 'btn btn-danger logout']
                )
                . Html::endForm()
    ])
    ?>
    <p>¿Estás seguro de que deseas borrar permanentemente su cuenta?</p>
    <?php Modal::end(); ?>
</div>
