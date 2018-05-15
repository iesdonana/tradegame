<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
$css = <<<CSS

body {
    background-color: #000;
    background-image: url();
}

video {
    position: fixed;
    right: 0;
    bottom: 0;
    top: 0;
    height: 100vh;
    min-width: 100%;
    object-fit:fill;
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
    position: fixed;
    right: 0;
    bottom: 0;
    top: 50px;
    background: rgba(0,0,0,0.5);
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
$js = <<<JS
var videos = 3;
var current = 1;
$('footer').remove();
$('.navbar-fixed-top.navbar input[type=text]').on('focus', function() {
    $(this).closest('.navbar').css('opacity', 1);
});
$('.navbar-fixed-top.navbar input[type=text]').on('focusout', function() {
    $(this).closest('.navbar').css('opacity', 0.5);
});
$('video').on('ended', function() {
    if (current + 1 > videos) {
        current = 0;
    }
    $(this).prop('src', '$path' + 'video' + (++current) + '.mp4')
});
JS;
$this->registerJs($js);
$this->title = 'TradeGame';
?>
<video src="<?= Url::to('@web/images/video1.mp4') ?>" autoplay muted>
</video>
<div class="content col-md-12">
    <div class="jumbotron">
        <div class="text-content text-center">
            <h1>TradeGame</h1>
            <h4><?= Yii::t('app', '¿Por qué dejar tus juegos guardados en un cajón pudiéndole sacar más partido?') ?></h4>
            <h3><?= Yii::t('app', 'Intercambia los videojuegos que ya no usas por otros de tu interés') ?></h3>
            <h4><?= Yii::t('app', 'Juega a infinidad de juegos sin gastar un sólo céntimo') ?></h4>
            <a href="<?= Url::to(['site/login']) ?>" class="boton"><?= Yii::t('app', 'Comienza') ?></a>
        </div>
    </div>
</div>
