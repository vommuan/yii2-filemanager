<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FilemanagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/vommuan/yii2-filemanager/assets/source';
    
    public $css = [
        'css/filemanager.css',
    ];
    
    public $js = [
        'js/filemanager.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'vommuan\filemanager\assets\PageHeaderAsset',
    ];
}
