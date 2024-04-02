<?php

namespace app\assets;

use yii\web\AssetBundle;

class CustomBootstrapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/custom.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
