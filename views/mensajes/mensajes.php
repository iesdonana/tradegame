<?php

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;


$css = <<<CSS
.fila {
    padding: 20px;
}

.panel-body.mensajes {
    background-color: #e5e5e5;
}

.mensaje span {
    border-radius: 10px;
    padding: 6px;
}

.suyo {
    background-color: #addeff;
}

.mio {
    background-color: #a4ff96;
}

.nav-pills > .active > a > .badge {
    color: #730000;
}
CSS;
$this->registerCss($css);

$create = Url::to(['mensajes/create']);
$url = Url::to(['mensajes/conversacion']);

$js = <<<JS
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
$('.pre-scrollable').scrollTop($('.pre-scrollable')[0].scrollHeight);
JS;
$this->registerJs($js, View::POS_READY);
?>
<div class="panel panel-default pre-scrollable">
    <div class="panel-body mensajes">
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
                        <span class="pull-right mio" data-toggle="tooltip" title="<?= $fecha ?>">
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
                        <span class="pull-left suyo" data-toggle="tooltip" title="<?= $fecha ?>">
                            <?= nl2br($msg) ?>
                        </span>
                    </div>
                <?php endif ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
