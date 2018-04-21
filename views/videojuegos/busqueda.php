<?php

use yii\helpers\Url;

$url = Url::to(['videojuegos/vista-busqueda']);
$js = <<<JS

$('input[type=checkbox]').on('click', function() {
    var plataformas = [];
    var generos = [];
    var desarrolladores = [];
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
    $.ajax({
        url: '$url',
        data: {
            plataformas: plataformas.join(','),
            generos: generos.join(','),
            desarrolladores: desarrolladores.join(','),
            q: $('#w2').val()
        },
        success: function(data) {
            $('.resultado-busqueda').html(data);
            topFunction();
        }
    });
});
JS;
$this->registerJs($js);
$this->registerCssFile('@web/css/checkbox.css');
?>

<div class="col-md-2">
    <div class="row">
        <div class="col-md-12">
            <h4>Plataformas</h4>
        </div>
    </div>
    <?php foreach ($plataformas as $key => $plat): ?>
        <div class="col-md-12">
            <?php $id = 'plat' . $plat->id ?>
            <input id="<?= $id ?>" type="checkbox" name="plataforma" value="<?= $plat->id ?>">
            <label for="<?= $id ?>"><?= $plat->nombre ?></label>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Géneros</h4>
        </div>
    </div>
    <?php foreach ($generos as $gen): ?>
        <div class="col-md-12">
            <?php $id = 'gen' . $gen->id ?>
            <input id="<?= $id ?>" type="checkbox" name="generos_videojuegos" value="<?= $gen->id ?>">
            <label for="<?= $id ?>"><?= $gen->nombre ?></label>
        </div>
    <?php endforeach; ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Desarrolladores</h4>
        </div>
    </div>
    <?php foreach ($desarrolladores as $des): ?>
        <div class="col-md-12">
            <?php $id = 'des' . $des->id ?>
            <input id="<?= $id ?>" type="checkbox" name="desarrolladores" value="<?= $des->id ?>">
            <label for="<?= $id ?>"><?= $des->compania ?></label>
        </div>
    <?php endforeach; ?>
</div>
<div class="col-md-10">
    <div class="panel panel-default">
        <div class="panel-body resultado-busqueda">
            <?= $this->render('listado_busqueda', [
                'dataProvider' => $dataProvider
            ]) ?>
        </div>
    </div>
</div>
