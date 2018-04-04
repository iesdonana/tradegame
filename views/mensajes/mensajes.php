<?php

use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;


$css = <<<CSS
.fila {
    padding: 20px;
}

.scrollable {
    background-color: #e5e5e5;
}

.mensaje span {
    padding: 6px;
    box-shadow: 2px 2px 1px grey;
}

span.suyo {
    background-color: #addeff;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    border-top-right-radius: 10px;
}

span.mio {
    background-color: #a4ff96;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    border-top-left-radius: 10px;
}

.nav-pills > .active > a > .badge {
    color: #730000;
}
.scrollable, .conversaciones {
    height: 60vh;
    overflow-y: scroll;
}

CSS;
$this->registerCss($css);

$create = Url::to(['mensajes/create']);
$url = Url::to(['mensajes/conversacion']);

$js = <<<JS
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
$('.scrollable').scrollTop($('.scrollable')[0].scrollHeight);
JS;
$this->registerJs($js, View::POS_READY);
?>
<div class="panel panel-default scrollable">
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
