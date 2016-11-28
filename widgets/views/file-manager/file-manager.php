<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\assets\FileManagerAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['moduleBundle'] = FileManagerAsset::register($this);

?>

<div class="file-manager">
	<div class="file-manager__header">
		<?= PageHeader::widget([
			'icon' => 'picture',
			'title' => Module::t('main', 'Files'),
		]);?>
	</div>

	<div class="file-manager__upload-form">
		<?= $this->render('file-manager__upload-form', [
			'uploadModel' => $uploadModel,
			'modalId' => $modalId,
		]);?>
	</div>

	<div class="file-manager__content">
		<?= $this->render('gallery', [
			'dataProvider' => $dataProvider,
			'modal' => $modal,
			'modalId' => $modalId,
			'multiple' => $multiple,
		]);?>
		
		<div class="file-details"></div>
	</div>
</div>