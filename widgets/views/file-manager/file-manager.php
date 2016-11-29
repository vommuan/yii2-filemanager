<?php

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

	<div class="row file-manager__content">
		<?= $this->render('gallery', [
			'dataProvider' => $dataProvider,
			'modal' => $modal,
			'modalId' => $modalId,
			'multiple' => $multiple,
		]);?>
		
		<div class="col-xs-12 col-sm-4 file-details"></div>
	</div>
</div>