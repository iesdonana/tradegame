<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?= Yii::t('app', 'Recuperar contraseña') ?>
        </div>
    </div>
    <div class="panel-body">
        <?= $this->render('_form_password', [
            'model' => $model
        ]) ?>
    </div>
</div>
