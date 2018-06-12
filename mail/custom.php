<?php

use yii\helpers\Html;

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

    body {
        background-color: #cecece;
        font-family: 'Raleway'
    }

    .row {
        margin-right: 200px;
        margin-left: 200px;
    }

    .texto {
        background-color: #efefef;
        padding: 10px;
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

    .boton, .oferta {
        text-decoration: none;
        cursor: pointer;
        display: block;
        margin-top: 20px;
        color: white;
        padding: 10px;
        text-align:center;
    }

    .oferta {
        background-color: #730000;
    }

    .boton {
        background-color: #248217;
    }
CSS;

    $this->registerCss($css);
    ?>
</head>
<body>
    <table align="center" width="600">
        <tr>
            <td class="titulo">
                <?= Html::img('https://raw.githubusercontent.com/jlnarvaez/tradegame/master/web/titulo.png', [
                    'height' => '80',
                ]) ?>
            </td>
        </tr>
        <tr>
            <td class="texto">
                ¡Hola, <strong><?= $usuario ?></strong>! <br>
                <?= $content ?>

            </td>
        </tr>
    </table>
</body>
</html>
