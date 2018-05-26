<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class VideAsset extends AssetBundle
{
    // public $basePath = '@webroot';
    public $sourcePath = '@npm/vide';
    public $baseUrl = '@web';
    public $js = [
        'dist/jquery.vide.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
