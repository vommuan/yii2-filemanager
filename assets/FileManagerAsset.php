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
        'vommuan\filemanager\assets\FileGalleryAsset',
        'vommuan\filemanager\assets\CropImageAsset',
        'vommuan\filemanager\assets\FontAwesomeAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
