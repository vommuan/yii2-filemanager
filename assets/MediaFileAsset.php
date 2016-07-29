<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class MediaFileAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks';
    
    public $css = [
        'media-file/media-file.css',
    ];
    
    public $js = [
		'media-file/media-file.js',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}