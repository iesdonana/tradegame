<?php

use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Url;

$url = Url::to(['videojuegos/vista-busqueda']);
$cargarMas = Yii::t('app', 'Cargar más resultados');
$this->registerCssFile('@web/css/pacman.css');
$js = <<<JS
var plataformas = [];
var generos = [];
var desarrolladores = [];

/**
 * Comprueba si aún hay más resultados por mostrar en pantalla, y si es así
 * incluye el botón para cargar más resultados.
 */
function comprobarMasResultados()
{
    if ($('.videojuego-item').length < $('#res-totales').data('total')) {
        var cont = $('<div></div>');
        cont.addClass('container-cargar');
        var boton = $('<a>$cargarMas </a>');
        boton.addClass('btn btn-xs btn-tradegame cargar-mas center-block cargaForm');
        cont.append($('<hr>'));
        cont.append(boton);
        $('.resultado-busqueda').append(cont);
    }
}

/**
 * Recoge los datos en los arrays globales dependiendo de los checkbox que se
 * hayan marcado
 */
function recogeDatosCheck()
{
    plataformas = [];
    generos = [];
    desarrolladores = [];
    $('input[type=checkbox]:checked').each(function() {
        var name = $(this).attr('name');
        if (name == 'plataforma') {
            plataformas.push($(this).val());
        } else if (name == 'generos_videojuegos') {
            generos.push($(this).val());
        } else if (name == 'desarrolladores') {
            desarrolladores.push($(this).val());
        }
    });
}
JS;
$this->registerJs($js, View::POS_HEAD);
$js = <<<JS
$('input[type=checkbox]').on('click', function() {
    recogeDatosCheck();
    $.ajax({
        url: '$url',
        data: {
            plataformas: plataformas.join(','),
            generos: generos.join(','),
            desarrolladores: desarrolladores.join(','),
            q: $('#w2').val()
        },
        beforeSend: function () {
            $('.container-loader').removeClass('hidden');
        },
        success: function(data) {
            $('.container-loader').addClass('hidden');
            $('.resultado-busqueda').html(data);
            comprobarMasResultados();
            topFunction();
        }
    });
});

$('.filtros h4.col').on('click', function() {
    var filtrosElems = $(this).closest('.row').next();
    if (filtrosElems.css('display') == 'block') {
        $(this).find('svg').remove();
        $(this).append('<i class="fa fa-angle-down"></i>')
        filtrosElems.slideUp();
    } else {
        $(this).find('svg').remove();
        $(this).append('<i class="fa fa-angle-up"></i>')
        filtrosElems.slideDown();
    }
});

comprobarMasResultados();

$('.resultado-busqueda').on('click', '.cargar-mas', function() {
    recogeDatosCheck();
    var cnt = $(this).parent();
    var btn = $(this);
    $.ajax({
        url: '$url',
        data: {
            salto: $('.videojuego-item').length,
            plataformas: plataformas.join(','),
            generos: generos.join(','),
            desarrolladores: desarrolladores.join(','),
            q: $('.twitter-typeahead .tt-input').val()
        },
        beforeSend: function () {
            btn.attr('disabled', true);
            var i = $('<i></i>');
            i.addClass('fa fa-spinner fa-spin');
            btn.append(i);
        },
        success: function(data) {
            cnt.remove();
            var hr = $('<hr>');
            hr.addClass('separador');
            $('.resultado-busqueda').append(hr).append(data);
            if ($('.videojuego-item').length < $('#res-totales').data('total')) {
                $('.resultado-busqueda').append(cnt);
                btn.attr('disabled', false);
                btn.children('svg').remove();
            }
        }
    });
});

if (screen.width <= 768) {
    $('.filtros h4.col').trigger('click');
}
JS;
$this->registerJs($js);
$this->registerCssFile('@web/css/checkbox.css');
?>

<div class="col-md-2 filtros">
    <div class="row">
        <div class="section-mini-title">
            <h4><?= Yii::t('app', 'Filtros') ?></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="col"><?= Yii::t('app', 'Plataformas') ?> <?= Utiles::FA('angle-up') ?></h4>
        </div>
    </div>
    <div class="row filtros-datos">
        <?php foreach ($plataformas as $key => $plat): ?>
            <div class="col-md-12">
                <?php $id = 'plat' . $plat->id ?>
                <input id="<?= $id ?>" type="checkbox" name="plataforma" value="<?= $plat->id ?>">
                <label for="<?= $id ?>"><?= $plat->nombre ?></label>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="col"><?= Yii::t('app', 'Géneros') ?> <?= Utiles::FA('angle-up') ?></h4>
        </div>
    </div>
    <div class="row filtros-datos">
        <?php foreach ($generos as $gen): ?>
            <div class="col-md-12">
                <?php $id = 'gen' . $gen->id ?>
                <input id="<?= $id ?>" type="checkbox" name="generos_videojuegos" value="<?= $gen->id ?>">
                <label for="<?= $id ?>"><?= Yii::t('app', $gen->nombre) ?></label>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="col"><?= Yii::t('app', 'Desarrolladores') ?> <?= Utiles::FA('angle-up') ?></h4>
        </div>
    </div>
    <div class="row filtros-datos">
        <?php foreach ($desarrolladores as $des): ?>
            <div class="col-md-12">
                <?php $id = 'des' . $des->id ?>
                <input id="<?= $id ?>" type="checkbox" name="desarrolladores" value="<?= $des->id ?>">
                <label for="<?= $id ?>"><?= $des->compania ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="col-md-10 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body resultado-busqueda">
            <?= $this->render('listado_busqueda', [
                'dataProvider' => $dataProvider,
                'resultadosTotales' => $resultadosTotales
            ]) ?>
        </div>
    </div>
</div>
<div class="container-loader hidden">
    <div class="loader-pacman">
      <div class="circles">
        <span class="one"></span>
        <span class="two"></span>
        <span class="three"></span>
      </div>
      <div class="pacman">
        <span class="top"></span>
        <span class="bottom"></span>
        <span class="left"></span>
        <div class="eye"></div>
      </div>
    </div>
</div>
