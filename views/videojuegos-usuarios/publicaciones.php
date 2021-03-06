<?php
use yii\helpers\Html;

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Publicaciones');

if (isset($model)) {
    $label = $model->usuario;
    $this->params['breadcrumbs'][] = [
        'label' => Html::encode($label),
        'url' => ['usuarios/perfil', 'usuario' => $label]
    ];
}

$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS
.panel-default {
    padding: 20px;
}

.txt-empty {
    margin-bottom: 10px;
}
CSS;
$this->registerCss($css);
$js = <<<JS
var container = $('<div class="row"></div>');
var subcontainer = $('<div class="col-md-12 text-center"></div>');
container.append(subcontainer);
$('.pagination').wrap(container);
JS;
$this->registerJs($js);
?>

<div class="col-md-offset-1 col-md-10">
    <div class="panel panel-default">
        <?= ListView::widget([
            'summary' => '',
            'dataProvider' => $dataProvider,
            'itemView' => 'view_min',
            'separator' => '<hr class="separador">',
            'emptyText' => '<div class="row">' .
                '<div class="col-md-12 text-center txt-empty">' .
                    Yii::t('app', 'Parece no tienes videojuegos publicados ¿A qué esperas?') .
                '</div>' .
                '<div class="col-md-12 text-center">' .
                    Html::a('Publicar nuevo videojuego', ['videojuegos-usuarios/publicar'], ['class' => 'btn btn-tradegame']) .
                '</div>' .
                '</div>'
            ]) ?>

    </div>
</div>
