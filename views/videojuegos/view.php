<?php

$css = <<<CSS
.datos-videojuego {
    margin: 10px;
}

.panel-default {
    margin-top: 20px;
    padding-right:20px;
    padding-left: 20px;
}
CSS;
$this->registerCss($css);

$this->title = $model->nombre;

$this->params['breadcrumbs'][] = [
    'label' => 'Videojuegos',
    'url' => ['videojuegos/buscador-videojuegos']
];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <div class="panel panel-default">
            <?= $this->render('datos', [
                'model' => $model
            ]) ?>
            <div class="row datos-videojuego">
                <h4 class="text-tradegame">Videojuegos publicados:</h4>
            </div>
            <div class="row datos-videojuegos">
                <?= $this->render('/videojuegos-usuarios/publicaciones_videojuego', [
                    'dataProvider' => $dataProvider
                ]) ?>
            </div>
        </div>
    </div>
</div>
