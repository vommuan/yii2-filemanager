<?php

use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FileManagerAsset;
use vommuan\filemanager\widgets\FileManager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Files');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'File manager'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= FileManager::widget();?>