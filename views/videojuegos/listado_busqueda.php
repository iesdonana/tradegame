<?php

use yii\widgets\ListView;

?>
<span id="res-totales" data-total="<?= $resultadosTotales ?>"></span>
<?= ListView::widget([
    'summary' => '',
    'dataProvider' => $dataProvider,
    'viewParams' => ['busqueda' => true],
    'itemView' => '/videojuegos-usuarios/view_min',
    'separator' => '<hr class="separador">'
]) ?>
