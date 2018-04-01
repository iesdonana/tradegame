<?php

use yii\helpers\Html;


$css = <<<CSS
.fila {
    padding: 20px;
}

.panel-body {
    background-color: #e5e5e5;
}

.mensaje span {
    background-color: cyan;
    border-radius: 10px;
    padding: 6px;
}
CSS;
$this->registerCss($css);
$js = <<<JS
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});
JS;
$this->registerJs($js);
?>

<div class="panel panel-default pre-scrollable">
    <div class="panel-body">
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
                    <div class="col-md-10 mensaje">
                        <span class="pull-right" data-toggle="tooltip" title="<?=$fecha ?>">
                            <?= $msg ?>
                        </span>
                    </div>
                    <div class="col-md-1">
                        <span><?= $avatar ?></span>
                    </div>
                <?php else: ?>
                    <div class="col-md-1">
                        <span><?= $avatar ?></span>
                    </div>
                    <div class="col-md-10 mensaje">
                        <span class="pull-left" data-toggle="tooltip" title="<?= $fecha ?>">
                            <?= $msg ?>
                        </span>
                    </div>
                <?php endif ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
