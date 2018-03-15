<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class FAAsset extends AssetBundle
{
    public $sourcePath = '@npm/font-awesome';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'svg-with-js/js/fontawesome-all.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
