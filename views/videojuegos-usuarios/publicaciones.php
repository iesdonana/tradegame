<?php
use yii\helpers\Html;

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Publicaciones');
$label = $model->usuario;

$this->params['breadcrumbs'][] = [
    'label' => Html::encode($label),
    'url' => ['usuarios/perfil', 'usuario' => $model->usuario]
];

$this->params['breadcrumbs'][] = $this->title;
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
            'itemView' => 'view_min',
            'separator' => '<hr class="separador">'
            ]) ?>

    </div>
</div>
