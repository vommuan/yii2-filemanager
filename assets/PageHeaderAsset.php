<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class PageHeaderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/vommuan/yii2-filemanager/assets/module.blocks';
    
    public $css = [
        'page-header/page-header.css',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}