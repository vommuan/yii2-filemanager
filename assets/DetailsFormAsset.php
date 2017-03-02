<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class DetailsFormAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks/details-form';
    
    public $css = [
        'details-form.css',
    ];
    
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}