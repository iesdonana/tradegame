<?php
use app\helpers\Utiles;

use yii\helpers\Html;

use yii\bootstrap\Modal;


$css = <<<CSS
.datos-videojuego {
    margin: 10px;
}
CSS;
$this->registerCss($css);

$this->title = $model->nombre;

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Videojuegos'),
    'url' => ['videojuegos/buscador-videojuegos']
];

$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            $('#modal-delete').modal('show');
        });
        $('.caratula-detail').click(function(e) {
            e.preventDefault();
            $('#modal-caratula').modal('show');
        });

        if ($.cookie('panel') == 'true') {
            $('.panel-default.oculto').removeClass('oculto')
        }
    });"
);
?>

<div class="row">
    <div class="col-md-offset-1 col-md-10">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->esAdmin()): ?>
            <div class="row">
                <div class="col-md-12 text-right panel-admin">
                    <span class="title"><?= Yii::t('app', 'Panel admin') . ' ' . Utiles::FA('angle-down') ?></span>
                    <div class="panel panel-default oculto">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Html::a(
                                        Yii::t('app', 'Alta') .' ' . Utiles::FA('plus-circle'),
                                        ['videojuegos/update'],
                                        ['class' => 'btn btn-sm btn-success btn-block']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::a(
                                        Yii::t('app', 'Modificar') . ' ' . Utiles::FA('edit'),
                                        ['videojuegos/update', 'id' => $model->id],
                                        ['class' => 'btn btn-sm btn-primary btn-block']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::a(
                                        Yii::t('app', 'Borrar') . ' ' . Utiles::FA('trash'),
                                        ['videojuegos/delete', 'id' => $model->id],
                                        ['class' => 'btn btn-sm btn-danger btn-block popup-modal']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <div class="panel panel-default panel-trade">
            <div class="panel-body" itemscope itemtype="http://schema.org/VideoGame">
                <?= $this->render('datos', [
                    'model' => $model
                ]) ?>
                <div class="row datos-videojuego">
                    <h4 class="text-tradegame"><?= Yii::t('app', 'Videojuegos publicados') ?>:</h4>
                </div>
                <div class="row datos-videojuegos">
                    <?= $this->render('/videojuegos-usuarios/publicaciones_videojuego', [
                        'dataProvider' => $dataProvider
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
 'id'     => 'modal-caratula',
 ])
 ?>
 <p class="text-center"><?= Html::img($model->caratula) ?></p>
 <?php Modal::end(); ?>
<?php Modal::begin([
 'header' => "<h2 class='modal-title'>" . Yii::t('app', 'Borrar videojuego') . "</h2>",
 'id'     => 'modal-delete',
 'footer' => Html::beginForm(['/videojuegos/remove', 'id' => $model->id], 'post') .
             Html::submitButton(
                 Utiles::FA('trash-alt') . ' ' . Yii::t('app', 'Borrar definitivamente'),
                 ['class' => 'btn btn-danger logout']
             )
             . Html::endForm()
 ])
 ?>
 <p><?= Yii::t('app', '¿Estás seguro de que deseas borrar permanentemente este juego?') ?></p>
 <?php Modal::end(); ?>
