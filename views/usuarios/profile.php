<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $model app\models\Usuarios */
?>
<div class="col-md-12">
<div class="panel panel-default">
   <div class="panel-heading resume-heading">
      <div class="row">
         <div class="col-lg-12">
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?= Html::img('@web/avatar.png', [
                            'id' => 'img-profile',
                            'class' => 'img-responsive'
                        ]) ?>
                </div>
            <div class="col-xs-12 col-sm-8 col-md-8">
               <ul class="list-group">
                    <?php $datos = $model->usuariosDatos ?>
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
                    <a href="#" class="btn btn-default">
                       <span class="glyphicon glyphicon-edit"></span>
                       <span>Editar perfil</span>
                    </a>
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
</div>
