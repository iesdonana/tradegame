<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */

$this->title = 'Registrar usuario';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-offset-3 col-md-6">
    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <?= $this->title ?>
            </div>
        </div>
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
                ]) ?>

        </div>
    </div>
</div>
