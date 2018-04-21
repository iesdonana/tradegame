<?php

use yii\widgets\ListView;

?>

<?= ListView::widget([
    'summary' => '',
    'dataProvider' => $dataProvider,
    'viewParams' => ['busqueda' => true],
    'itemView' => '/videojuegos-usuarios/view_min',
    'separator' => '<hr class="separador">'
]) ?>
