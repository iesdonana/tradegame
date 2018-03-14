<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class BxAsset extends AssetBundle
{
    // public $basePath = '@webroot';
    public $sourcePath = '@npm/bxslider';
    public $baseUrl = '@web';
    public $css = [
        'dist/jquery.bxslider.min.css',
    ];
    public $js = [
        'dist/jquery.bxslider.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
