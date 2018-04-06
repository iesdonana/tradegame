<?php
/* @var $this yii\web\View */
/* @var $model app\models\Reportes */
use yii\helpers\Html;


$this->title = 'Create Reportes';
$this->params['breadcrumbs'][] = ['label' => 'Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reportes-create">


    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title">
                        Reportar a <em><?= Html::encode($reportado->usuario) ?></em>
                    </div>
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
