<?php
namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class SettingAsset extends AssetBundle
{
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'vommuan\filemanager\assets\PageHeaderAsset',
    ];
}