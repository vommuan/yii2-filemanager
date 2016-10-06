<?php

use yii\helpers\Html;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FileManagerAsset;
use vommuan\filemanager\widgets\FileManager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Files');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'File manager'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

$widgetId = 'standalone-filemanager';

$this->registerJs("(new FileManager()).init({
	'widget': $('#{$widgetId}')
});");

?>
<?= Html::beginTag(
	'div', [
		'id' => $widgetId
	]
);?>
	<?= FileManager::widget();?>
<?= Html::endTag('div');?>