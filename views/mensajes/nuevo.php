<?php

?>

<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-default panel-trade">
            <div class="panel-heading">
                <div class="panel-title">
                    Enviar mensaje
                </div>
            </div>
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model
                    ]) ?>
            </div>
        </div>
    </div>
</div>
