<?php

use yii\widgets\ListView;

?>

<div class="col-md-offset-1 col-md-10">
    <?= ListView::widget([
        'summary' => '',
        'dataProvider' => $dataProvider,
        'viewParams' => ['busqueda' => true],
        'itemView' => '/videojuegos-usuarios/view',
        'separator' => '<hr class="separador">'
    ]) ?>
</div>
