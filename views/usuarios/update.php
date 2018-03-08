<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $seccion string*/

$this->title = 'Modificar perfil';
$this->params['breadcrumbs'][] = [
    'label' => 'Mi perfil',
    'url' => ['usuarios/perfil', 'usuario' => Yii::$app->user->identity->usuario]
];
$this->params['breadcrumbs'][] = $this->title;

$js = <<<EOT
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
EOT;
$this->registerJs($js);
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('panel', [
            'model' => $model,
            ]) ?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title text-center">
                    Datos de la cuenta
                </div>
            </div>
            <div class="panel-body">
                <?php if ($seccion !== 'personal'): ?>
                    <?php $dir = '/usuarios-datos/_form_personal' ?>
                    <?= $this->render('_form_' . $seccion, [
                        'model' => $model,
                        ]) ?>
                <?php else: ?>
                    <?= $this->render('/usuarios-datos/_form_personal', [
                        'model' => $model,
                        ]) ?>
                <?php endif ?>

                </div>
            </div>
        </div>
</div>
