<?php

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
?>
<div class="panel panel-default panel-trade">
    <div class="panel-heading">
        <div class="panel-title text-center">
            Registrar usuario
        </div>
    </div>
    <div class="panel-body">
        <?= $this->render('_form_datos', [
            'model' => $model,
            ]) ?>
    </div>
</div>
