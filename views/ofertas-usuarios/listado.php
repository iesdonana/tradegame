<?php
$js = <<<EOT
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
EOT;
$this->registerJs($js);
?>

<div class="ofertas-recibidas">
    <div class="row">
        <div class="col-md-2">
            <?= $this->render('panel') ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default panel-trade">
                <div class="panel-heading">
                    <div class="panel-title text-center">
                        Ofertas recibidas
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
