<?php
namespace app\assets;

use yii\web\AssetBundle;

class CustomSlickAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/custom-slick.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
