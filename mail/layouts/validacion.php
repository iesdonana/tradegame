<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<?php
    $css = <<<'CSS'
        .titulo {
            background-color: grey;
        }

        .row {
            margin-right: 200px;
            margin-left: 200px;
        }

        .row .texto {
            border: 2px dashed grey;
        }

        .texto {
            padding: 20px;
        }

        img {
            display: block;
            margin: auto;
        }

        .url {
            font-weight: bold;
        }

        a.boton:hover {
            color: white;
            text-decoration: none;
            background-color: #55a34b;
        }

        .boton {
            text-decoration: none;
            cursor: pointer;
            display: block;
            margin-top: 20px;
            background-color: #248217;
            color: white;
            padding: 10px;
            text-align:center;
        }
CSS;

    $this->registerCss($css);
    ?>
</head>
<body>
    <div class="row titulo">
        <?= Html::img('https://raw.githubusercontent.com/jlnarvaez/tradegame/master/web/titulo.png', [
            'height' => '80px',
        ]) ?>
    </div>
    <div class="row">
        <div class="texto">
            ¡Hola, <?= $this->params['usuario'] ?>!
            Bienvenido a <?= Html::a('TradeGame', Url::home('http'), ['class' => 'url']) ?><br>
            Para completar el registro en TradeGame debes validar tu cuenta, y
            así poder iniciar sesión en nuestro sitio web.
            Para validar tu cuenta haz click en el siguiente botón:<br><br><br>
            <?= Html::a('Validar cuenta', $this->params['url'], ['class' => 'boton']) ?>
        </div>
    </div>
</body>
</html>
