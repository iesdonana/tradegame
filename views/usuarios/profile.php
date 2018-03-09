<?php

/* @var $this yii\web\View */
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;

/* @var $model app\models\Usuarios */
$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            var modal = $('#modal-delete').modal('show');
            modal.find('.modal-body').load($('.modal-dialog'));
            modal.find('.modal-title').text('Supprimer la ressource');

            $('#delete-confirm').click(function(e) {
                e.preventDefault();
                window.location = 'delete?id='+id;
            });
        });
    });"
);
?>
<div class="col-md-12">
<div class="panel panel-default">
   <div class="panel-heading resume-heading">
      <div class="row">
         <div class="col-lg-12">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php $datos = $model->usuariosDatos; ?>
                    <?= Html::img($datos->avatar, [
                            'id' => 'img-profile',
                            'class' => 'img-responsive'
                        ]) ?>
                </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
               <ul class="list-group">
                    <li class="list-group-item"><strong>Usuario:</strong> <?= Html::encode($model->usuario) ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?= Html::encode($model->email) ?></li>
                    <?php if ($datos->nombre_real): ?>
                        <li class="list-group-item"><strong>Nombre:</strong> <?= Html::encode($datos->nombre_real) ?></li>
                    <?php endif ?>
                    <?php if ($datos->localidad): ?>
                        <li class="list-group-item"><strong>Localidad:</strong> <?= Html::encode($datos->localidad) ?></li>
                    <?php endif ?>
                    <?php if ($datos->fecha_nacimiento): ?>
                        <li class="list-group-item"><strong>Fecha de nacimiento:</strong> <?= $datos->fecha_nacimiento ?></li>
                    <?php endif ?>
               </ul>
                <?php if ($model->id === Yii::$app->user->id): ?>
                    <?= Html::a(
                        Utiles::glyphicon('edit') . ' Editar perfil',
                        ['usuarios/update', 'usuario' => Yii::$app->user->identity->usuario],
                        ['class' => 'btn btn-default']
                    ) ?>
                    <?= Html::a(
                        Utiles::glyphicon('remove') . ' Borrar cuenta',
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
    <?php if ($datos->biografia): ?>
        <div class="bs-callout bs-callout-danger">
           <h4>Biografía</h4>
           <p><?= Html::encode($datos->biografia) ?></p>
        </div>
    <?php endif ?>

   </div>
   <?php Modal::begin([
    'header' => '<h2 class="modal-title">Borrar cuenta</h2>',
    'id'     => 'modal-delete',
    'footer' => Html::beginForm(['/usuarios/remove'], 'post') .
                Html::submitButton(
                    Utiles::glyphicon('remove') . ' Borrar definitivamente',
                    ['class' => 'btn btn-danger logout']
                )
                . Html::endForm()
    ])
    // 'footer' => Html::a(Utiles::glyphicon('remove') .
    //             ' Borrar definitivamente', ['usuarios/remove'],
    //             ['class' => 'btn btn-danger', 'id' => 'delete-confirm']),
    // ]);
    ?>


    <p>¿Estás seguro de que deseas borrar permanentemente su cuenta?</p>

    <?php Modal::end(); ?>
</div>
