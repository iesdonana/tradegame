<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle
{
    // public $basePath = '@webroot';
    public $sourcePath = '@npm/slick-carousel';
    public $baseUrl = '@web';
    public $css = [
        'slick/slick.css',
        'slick/slick-theme.css',
    ];
    public $js = [
        'slick/slick.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
