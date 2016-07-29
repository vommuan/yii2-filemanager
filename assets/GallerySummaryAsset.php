<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class GallerySummaryAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks/gallery-summary';
    
    public $js = [
		'gallery-summary.js',
    ];
}