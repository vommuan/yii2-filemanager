<?php

use yii\helpers\Html;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FilemanagerAsset;

/* @var $this yii\web\View */

$this->title = Module::t('main', 'File manager');
$this->params['breadcrumbs'][] = $this->title;

$assetPath = FilemanagerAsset::register($this)->baseUrl;
?>

<div class="filemanager-default-index">
	<div class="page-header">
		<h1><?= Module::t('main', 'File manager');?></h1>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="text-center">
                <h2>
                    <?= Html::a(Module::t('main', 'Files'), ['file/index']) ?>
                </h2>
                <?= Html::a(
                    Html::img($assetPath . '/images/files.png'), 
                    ['file/index']
                ) ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="text-center">
                <h2>
                    <?= Html::a(Module::t('main', 'Settings'), ['setting/index']) ?>
                </h2>
                <?= Html::a(
                    Html::img($assetPath . '/images/settings.png'), 
                    ['setting/index']
                ) ?>
            </div>
        </div>
    </div>
</div>