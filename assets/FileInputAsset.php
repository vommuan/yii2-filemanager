<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/source';

    public $js = [
        'js/fileinput.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'vommuan\filemanager\assets\ModalAsset',
    ];
}
