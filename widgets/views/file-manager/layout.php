<?php

use vommuan\filemanager\assets\FileManagerAsset;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $widgetId string */
/* @var $multiple string */
/* @var $this yii\web\View */
/* @var $uploadModel vommuan\filemanager\models\UploadFileForm */

FileManagerAsset::register($this);

?>

<div class="file-manager">
	<div class="file-manager__header">
		<?= PageHeader::widget([
			'icon' => 'picture',
			'title' => Module::t('main', 'Files'),
		]);?>
	</div>

	<div class="file-manager__upload-form">
		<?= $this->render('_upload-form', [
			'uploadModel' => $uploadModel,
			'widgetId' => $widgetId,
		]);?>
	</div>

	<div class="row file-manager__content">
		<div class="col-xs-12 col-sm-8">
			<?= $this->render('_gallery', [
				'dataProvider' => $dataProvider,
				'widgetId' => $widgetId,
				'multiple' => $multiple,
			]);?>
		</div>
		
		<div class="col-xs-12 col-sm-4">
			<div class="file-details"></div>
		</div>
	</div>
</div>