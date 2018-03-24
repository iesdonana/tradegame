<?php
$js = <<<EOT
$('.filtros li').removeClass('active');
var array = window.location.pathname.split('/');
if (array.length > 0) {
    var encontrado = false;
    $('.filtros a').each(function(e) {
        if ($(this).data('seccion') === array[array.length - 1]) {
            encontrado = true;
            $(this).parent('li').addClass('active');
        }
    });
}
if (!encontrado) {
    $('.filtros a').first().parent('li').addClass('active');
}
EOT;
$this->registerJs($js);
?>
<div class="valoraciones-recibidas">
    <div class="row">
        <div class="col-md-2">
            <?= $this->render('panel') ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        Mis valoraciones
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
