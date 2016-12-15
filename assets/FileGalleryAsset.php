<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileGalleryAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks/file-gallery';
    
    public $css = [
        'file-gallery.css',
    ];
    
    public $js = [
		'file-gallery.js',
    ];
    
    public $depends = [
        'vommuan\filemanager\assets\GalleryPagerAsset',
        'vommuan\filemanager\assets\GallerySummaryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}