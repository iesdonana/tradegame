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
// $this->registerJsFile('@web/js/jquery.cookie.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$this->title = Html::encode($model->nombre);

$this->params['breadcrumbs'][] = [
    'label' => 'Videojuegos',
    'url' => ['videojuegos/buscador-videojuegos']
];

$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("
    $(function() {
        $('.popup-modal').click(function(e) {
            e.preventDefault();
            $('#modal-delete').modal('show');
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
                    <span class="title">Panel admin <?= Utiles::FA('angle-down') ?></span>
                    <div class="panel panel-default oculto">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Html::a(
                                        'Alta ' . Utiles::FA('plus-circle'),
                                        ['videojuegos/update'],
                                        ['class' => 'btn btn-sm btn-success btn-block']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::a(
                                        'Modificar ' . Utiles::FA('edit'),
                                        ['videojuegos/update', 'id' => $model->id],
                                        ['class' => 'btn btn-sm btn-primary btn-block']) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= Html::a(
                                        'Borrar ' . Utiles::FA('trash'),
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
            <div class="panel-body">
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
</div>
<?php Modal::begin([
 'header' => "<h2 class='modal-title'>Borrar videojuego</h2>",
 'id'     => 'modal-delete',
 'footer' => Html::beginForm(['/videojuegos/remove', 'id' => $model->id], 'post') .
             Html::submitButton(
                 Utiles::FA('trash-alt') . ' Borrar definitivamente',
                 ['class' => 'btn btn-danger logout']
             )
             . Html::endForm()
 ])
 ?>
 <p>¿Estás seguro de que deseas borrar permanentemente este juego?</p>
 <?php Modal::end(); ?>
