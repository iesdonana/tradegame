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
        <div class="panel panel-default panel-trade">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    <?php
                        $usuario = $model->usuarioValorado->usuario;
                        $urlPerfil = Html::a($usuario, ['usuarios/perfil', 'usuario' => $usuario]);
                    ?>
                    <?= Html::encode($this->title) ?>
                </div>
            </div>
            <div class="panel-body">
                Valora tu intercambio con el usuario <?= $urlPerfil ?><hr>
                <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>
                </div>
            </div>
    </div>
</div>
