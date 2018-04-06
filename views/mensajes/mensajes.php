<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->registerCssFile('@web/css/mini_loader.css');

$create = Url::to(['mensajes/create']);
$url = Url::to(['mensajes/conversacion']);
?>
<?php foreach ($lista as $mensaje): ?>
    <?php
    $opt = ['class' => 'img-chat img-circle'];
    $me = Yii::$app->user->id;
    $avatar = Html::img($mensaje->emisor->usuariosDatos->avatar, $opt);
    $msg = Html::encode($mensaje->contenido);
    $fecha = Yii::$app->formatter->asDatetime($mensaje->created_at);
    ?>
    <div class="row fila">
        <?php if ($mensaje->emisor_id == $me): ?>
            <div class="col-md-10 col-xs-8 mensaje">
                <span class="pull-right mio">
                    <?= nl2br($msg) ?>
                </span>
            </div>
            <div class="col-md-1 col-xs-3">
                <?= $avatar ?>
            </div>
        <?php else: ?>
            <div class="col-md-1 col-xs-3">
                <?= $avatar ?>
            </div>
            <div class="col-md-10 col-xs-8 mensaje">
                <span class="pull-left suyo">
                    <?= nl2br($msg) ?>
                </span>
            </div>
        <?php endif ?>
    </div>
<?php endforeach; ?>
