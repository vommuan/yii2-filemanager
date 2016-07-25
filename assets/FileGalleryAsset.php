<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileGalleryAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks';
    
    public $css = [
        'file-gallery/file-gallery.css',
    ];
    
    public $js = [
		'file-gallery/file-gallery.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'vommuan\filemanager\assets\MediaFileAsset',
    ];
}