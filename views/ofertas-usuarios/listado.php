<?php
use yii\helpers\Url;


$urlOfertas = Url::to(['ofertas-usuarios/index']);

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
$(document).on('click',  '.not-active span', function() {
    var este = $(this).parent('.not-active');
    $.ajax({
        url: '$urlOfertas',
        data: {
            tipo: $('.not-active span').text().trim().toLowerCase(),
            estado: $('.nav-pills .active a').data('seccion')
        },
        beforeSend: function() {
            // Cambiamos de posición "Recibidas" y "Enviadas"
            este.animate({
                top: '-=19px',
                fontSize: '+=4px',
            }, 500);
            este.removeClass('not-active');
            var current = $('.active-page');
            este.addClass('active-page');
            current.animate({
                top: '+=19px',
                fontSize: '-=4px',
            }, 500);
            current.addClass('not-active');
            current.removeClass('active-page');

            // Colocamos el loader
            este.closest('.panel-trade').children('.panel-body').addClass('loading');
        },
        success: function(data) {
            este.closest('.panel-trade').children('.panel-body').removeClass('loading');
            $('.grid-results').html(data);
            var tipo = $('.active-page span').text().trim().toLowerCase();
            var estado  = $('.nav-pills li.active a').data('seccion');
            window.history.pushState('', 'TradeGame', '/ofertas/' + tipo + '/' + estado);
            $('.nav-pills li a').each(function() {
                $(this).prop('href', '/ofertas/' + tipo + '/' + $(this).data('seccion'));
            });
        }
    })
});
JS;
$this->registerJs($js);
$css = <<<CSS
.not-active {
    font-size: 12px;
    color: rgb(115, 0, 0, 0.5);
    cursor: pointer;
    text-decoration: none;
}
.loading * {
    opacity: 0.8;
}

.loading .cont-load,
.loading .loader {
    opacity: 1;

}

.cont-load {
    position: absolute;
    left: 50%;
    z-index: 200;
}

.loader {
    display:none;
    position: relative;
    left: -50%;
}

.loading .loader {
    display: block;
}
CSS;
$this->registerCss($css);
$this->registerCssFile('@web/css/loader.css');
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
                                        <?= Yii::t('app', 'Recibidas') ?>
                                    <?php else: ?>
                                        <?= Yii::t('app', 'Enviadas') ?>
                                    <?php endif ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="cont-load">
                        <div class="loader center-block"></div>
                    </div>
                    <div class="grid-results">
                        <?= $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
