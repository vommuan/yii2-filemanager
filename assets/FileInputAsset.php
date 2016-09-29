<?php

namespace vommuan\filemanager\assets;

use yii\web\AssetBundle;

class FileInputAsset extends AssetBundle
{
    public $sourcePath = '@filemanager/assets/module.blocks/file-input';
	
	public $css = [
		'file-input.css',
	];
	
    public $js = [
        'file-input.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'vommuan\filemanager\assets\ModalAsset',
    ];
}
