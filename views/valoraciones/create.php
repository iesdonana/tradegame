<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Valoraciones */

$this->title = 'Valorar intercambio';
$this->params['breadcrumbs'][] = ['label' => 'Valoraciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="valoraciones-create">
    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    <?php
                        $oferta = $model->oferta;
                        $videojuegoPublicado = $oferta->videojuegoPublicado->videojuego;
                        $videojuegoOfrecido = $oferta->videojuegoOfrecido;
                        $usuario = $videojuegoOfrecido->usuario->usuario;
                        $urlPerfil = Html::a($usuario, ['usuarios/perfil', 'usuario' => $usuario]);
                    ?>
                    <?= Html::encode($this->title) ?>
                </div>
            </div>
            <div class="panel-body">
                Valora tu intercambio con el usuario <?= $urlPerfil ?>, en el cuál has intercambiado tu
                <em><?= $videojuegoPublicado->nombre ?></em> por su <em><?= $videojuegoOfrecido->videojuego->nombre ?></em><hr>
                <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>
                </div>
            </div>
    </div>
</div>
