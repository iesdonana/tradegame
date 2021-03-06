<?php
use yii\helpers\Html;


$this->title = $model->usuario;
$this->params['breadcrumbs'][] = [
    'label' => Html::encode($model->usuario),
    'url' => ['usuarios/perfil', 'usuario' => $model->usuario]
];

$this->params['breadcrumbs'][] = Yii::t('app', 'Valoraciones');
?>
<div class="valoraciones">
    <h2 class="text-center text-tradegame"><?= Html::encode(Yii::t('app', 'Valoraciones de') . ' ' . $this->title) ?></h2>
    <div class="row">
        <div class="col-md-12">
            <div class="sticky">
                <ul class="center-block">
                    <?= $this->render('notas', [
                        'valRecibidas' => $model->valoraciones
                        ]) ?>
                    </ul>
                </div>
        </div>
    </div>
</div>
