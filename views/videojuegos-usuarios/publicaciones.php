<?php
use yii\widgets\ListView;


$this->title = 'Mis publicaciones';
$label = 'Mi perfil';
if (Yii::$app->user->id !== $model->id) {
    $this->title = 'Publicaciones de ' . "'$model->usuario'";
    $label = 'Perfil de ' . "'$model->usuario'";
}

$this->params['breadcrumbs'][] = [
    'label' => $label,
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
