<?php

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $seccion string*/

$this->title = Yii::t('app', 'Modificar perfil');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Mi perfil'),
    'url' => ['usuarios/perfil', 'usuario' => Yii::$app->user->identity->usuario]
];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
$('.nav-pills li').removeClass('active');
var array = window.location.pathname.split('/');
if (array.length > 0) {
    $('.nav-pills a').each(function(e) {
        if ($(this).data('seccion') === array[array.length - 1]) {
            $(this).parent('li').addClass('active');
        }
    });
}

$('.panel-title').text($('.nav-pills .active > a').text());
JS;
$this->registerJs($js);

$model = isset($model) ? $model : $modelDatos->usuario;
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('panel', [
            'model' => $model,
            ]) ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default panel-trade">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    Datos de la cuenta
                </div>
            </div>
            <div class="panel-body">
                <?php if ($seccion !== 'personal'): ?>
                    <?= $this->render('_form_' . $seccion, [
                        'model' => $model,
                        ]) ?>
                <?php else: ?>
                    <?= $this->render('/usuarios-datos/_form_personal', [
                        'model' => $modelDatos,
                        'generos' => $generos
                        ]) ?>
                <?php endif ?>

            </div>
        </div>
    </div>
</div>
