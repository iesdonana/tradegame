<?php

/* @var $this yii\web\View */
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;

/* @var $model app\models\Usuarios */

use app\assets\BxAsset;

BxAsset::register($this);

$this->title = 'Mi perfil';

if (Yii::$app->user->id !== $model->id) {
    $this->title = 'Perfil de ' . "'$model->usuario'";
}

$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            var modal = $('#modal-delete').modal('show');

            $('#delete-confirm').click(function(e) {
                e.preventDefault();
                window.location = 'delete?id='+id;
            });
        });
    });"
);
$this->registerCssFile('@web/css/profile.css'); //{auto: true, stopAutoOnClick: true}
$this->registerJs("$('.bxslider').bxSlider();");
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
                    <a href="#" class="btn btn-primary">
                        <span class="glyphicon glyphicon-send"></span>
                        <span>Enviar mensaje</span>
                    </a>
                <?php endif ?>
            </div>
         </div>
      </div>
   </div>
   <?php if (count($listado) > 0): ?>
       <div class="bs-callout bs-callout-danger">
           <h4>Últimos videojuegos publicados</h4>
           <ul class='bxslider'>
                <?php foreach ($listado as $model): ?>
                    <li>
                        <div class="col-md-offset-1 col-md-10">
                            <?= $this->render('/videojuegos-usuarios/view', [
                                'model' => $model,
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
