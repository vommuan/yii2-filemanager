<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/source';
    
    public $css = [
        'css/filemanager.css',
    ];
    
    public $js = [
        'js/filemanager.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
