<?php
use app\assets\VideAsset;

use yii\helpers\Url;

/* @var $this yii\web\View */
$css = <<<CSS
body {
    background-color: #000;
    background-image: url();
}

.video {
    position: fixed;
    right: 0;
    bottom: 0;
    top: 0;
    min-width: 100%;
    object-fit:fill;
}

.background {
    background-color: rgba(0,0,0,0.5);
    top: 0;
    left: 0;
    margin: 0;
    width: 100%;
    height: 100%;
    position: fixed;
}

h1 {
    font-size: 10vw;
}

h4 {
    font-size: 1.5vw;
    margin: 1em;
}

h3 {
    font-size: 2vw;
    margin: 1em;
}


.content {
    right: 0;
    bottom: 0;
    color: #fff;
    width: 100%;
    min-height: 100%;
    padding: 20px;
}

.text-content {
    min-height: 100%;
}

a.boton {
  margin-top: 10px;
  z-index: 1;
  background-color: transparent;
  position: relative;
  display: inline-block;
  font-size: 3vw;
  padding: 15px 34px;
  color: #FFF;
  text-decoration: none;
  border: solid 5px #8C8C8C;
  box-shadow: 12px 12px 0  -5px #8C8C8C;
  transition: .3s;
   overflow: hidden;
  text-transform: uppercase;
  letter-spacing: 2px;
}

a.boton:before {
  display: inline-block;
  transition: bottom, 0.35s;
  position: absolute;
  transform: skew(-50deg);
  top: 0px;
  left: 260px;
  background-color: #8C8C8C;
  content: "　";
  width: 220px;
  height: 120px;
  z-index: -1;
}

a.boton:hover {
   box-shadow: 0px 0px 0  0px #8C8C8C;
}

a.boton:hover:before {
  top: 0.01em;
  left: 0.01em;
}

@media only screen and (min-width: 768px) {
    .navbar-fixed-top.navbar {
        opacity: 0.5;
    }

    .navbar-fixed-top.navbar:hover {
        opacity: 1;
    }
}


@media only screen and (max-width: 768px) {
    h1 {
        font-size: 12vw;
    }
    h4 {
        font-size: 5vw;
        margin: 1em;
    }
    h3 {
        font-size: 7vw;
        margin: 1em;
    }
    a.boton {
      font-size: 5vw;
    }
}
CSS;
$this->registerCss($css);
$path = Url::to('@web/images/');

VideAsset::register($this);

$js = <<<JS
$('footer').remove();
$('.navbar-fixed-top.navbar input[type=text]').on('focus', function() {
    $(this).closest('.navbar').css('opacity', 1);
});
$('.navbar-fixed-top.navbar input[type=text]').on('focusout', function() {
    $(this).closest('.navbar').css('opacity', 0.5);
});

$('.video').vide({
    mp4: '$path' + 'video.mp4'
}, {
    posterType: 'auto-detection; "none"'
});
JS;
$this->registerJs($js);
$this->title = 'TradeGame';
?>
<div class="video">
</div>
<div class="background">

</div>
<div class=" col-md-12">
    <div class="jumbotron">
        <div class="content text-content text-center">
            <h1>TradeGame</h1>
            <h4><?= Yii::t('app', '¿Por qué dejar tus juegos guardados en un cajón pudiéndole sacar más partido?') ?></h4>
            <h3><?= Yii::t('app', 'Intercambia los videojuegos que ya no usas por otros de tu interés') ?></h3>
            <a href="<?= Url::to(['site/login']) ?>" class="boton"><?= Yii::t('app', 'Comienza') ?></a>
        </div>
    </div>
</div>
