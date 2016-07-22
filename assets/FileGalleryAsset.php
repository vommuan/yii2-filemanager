<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileGalleryAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks';
    
    public $css = [
        'file-gallery/file-gallery.css',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}