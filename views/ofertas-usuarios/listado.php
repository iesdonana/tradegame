<?php
use yii\helpers\Html;


$js = <<<JS
$('.nav-pills li').removeClass('active');
var array = window.location.pathname.split('/');
if (array.length > 0) {
    var encontrado = false;
    $('.nav-pills a').each(function(e) {
        if ($(this).data('seccion') === array[array.length - 1]) {
            encontrado = true;
            $(this).parent('li').addClass('active');
        }
    });
}
if (!encontrado) {
    $('.nav-pills a').first().parent('li').addClass('active');
}

// Cambiamos de posición el "Recibidas" y "Enviadas"
// $(document).on('click',  '.not-active span', function() {
//     var este = $(this).parent('.not-active');
//     este.animate({
//         top: '-=20px',
//         fontSize: '+=4px',
//     }, 500);
//     este.removeClass('not-active');
//     var current = $('.active-page');
//     este.addClass('active-page');
//     current.animate({
//         top: '+=20px',
//         fontSize: '-=4px',
//     }, 500);
//     current.addClass('not-active');
//     current.removeClass('active-page');
// });
JS;
$this->registerJs($js);
$css = <<<CSS
.not-active a {
    font-size: 12px;
    color: rgb(115, 0, 0, 0.5);
    cursor: pointer;
    text-decoration: none;
}
CSS;
$this->registerCss($css);
$ofEnviadas = Yii::$app->request->get('tipo') === 'enviadas';
$estado = Yii::$app->request->get('estado');
?>

<div class="ofertas-recibidas">
    <div class="row">
        <div class="col-md-2">
            <?= $this->render('panel') ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="row">
                            <div class="col-md-12 text-center active-page">
                                <span>
                                    <?php if ($ofEnviadas): ?>
                                        <?= Yii::t('app', 'Enviadas') ?>
                                    <?php else: ?>
                                        <?= Yii::t('app', 'Recibidas') ?>
                                    <?php endif ?>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center not-active">
                                <span>
                                    <?php if ($ofEnviadas): ?>
                                        <?= Html::a('Recibidas', [
                                            '/ofertas-usuarios/index',
                                            'tipo' => 'recibidas',
                                            'estado' => $estado
                                        ]) ?>
                                    <?php else: ?>
                                        <?= Html::a('Enviadas', [
                                            '/ofertas-usuarios/index',
                                            'tipo' => 'enviadas',
                                            'estado' => $estado
                                        ]) ?>
                                    <?php endif ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <?= $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
