<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileManagerAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks/file-manager';
    
    public $css = [
        'file-manager.css',
    ];
    
    public $js = [
        'file-manager.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'vommuan\filemanager\assets\FileGalleryAsset',
    ];
}
