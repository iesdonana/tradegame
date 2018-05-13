<?php

use app\helpers\Utiles;

use yii\web\View;

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Ofertas */
/* @var $form yii\widgets\ActiveForm */

$usuario_id = (isset($usuario_id) ? $usuario_id : Yii::$app->user->id);
$defaultImg = '/' . Yii::getAlias('@caratulas') . '/default.png';
$this->registerJs("var defaultImg = '$defaultImg'", View::POS_HEAD);
$this->registerJsFile('@web/js/publicar.js', ['position' => View::POS_HEAD]);
$this->registerJsFile('@web/js/utiles.js');
$this->registerJsFile('@web/js/oferta.js', ['position' => View::POS_HEAD]);
$url = Url::to(['videojuegos-usuarios/buscar-publicados',
    'id_usuario' => $usuario_id,
    'id_videojuego' => $model->videojuegoPublicado->videojuego->id
]);

$urlDatos = Url::to(['videojuegos/oferta-videojuego']);
?>

<div class="ofertas-form">

    <?php $form = ActiveForm::begin(['options' => [
        'class' => 'cargaForm'
    ]]); ?>

    <?= $form->field($model, 'videojuego_publicado_id', ['template' => '{input}'])
        ->hiddenInput()->label(false) ?>
        <?php $items = [
            'language' => Yii::$app->language,
            'pluginEvents' => [
                'select2:select' => "function() {
                    peticionVideojuego('$urlDatos');
                }",
                "select2:unselect" => "function() { vaciarDatos(); }"
            ],
        ];
        $items = array_merge($items, Utiles::optionsSelect2($url)) ?>

        <?= $form->field($model, 'videojuego_ofrecido_id')
            ->widget(Select2::classname(), $items)->label(false); ?>
    <div class="form-group col-md-offset-2 col-md-8">
        <?= Html::submitButton(Yii::t('app', 'Enviar oferta') . ' ', ['class' => 'btn btn-tradegame btn-block']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
