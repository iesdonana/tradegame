<?php

use yii\widgets\ListView;

$css = <<<CSS
.panel-default {
    padding: 20px;
}
CSS;
$this->registerCss($css);
?>

<div class="col-md-offset-1 col-md-10">
    <div class="panel panel-default">
        <?= ListView::widget([
            'summary' => '',
            'dataProvider' => $dataProvider,
            'viewParams' => ['busqueda' => true],
            'itemView' => '/videojuegos-usuarios/view',
            'separator' => '<hr class="separador">'
            ]) ?>
    </div>
</div>
