<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class ModalAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/source';
    public $css = [
        'css/modal.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
