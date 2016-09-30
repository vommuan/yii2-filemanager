<?php

use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\widgets\FileGallery;
use vommuan\filemanager\assets\FileManagerAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['moduleBundle'] = FileManagerAsset::register($this);
?>

<?= PageHeader::widget([
	'icon' => 'picture',
	'title' => Module::t('main', 'Files'),
]);?>

<div class="row">
	<div class="col-xs-12">
		<?= $this->render('file-manager__upload-form', [
			'uploadModel' => $uploadModel,
		]);?>
	</div>
</div>

<?= FileGallery::widget([
	'dataProvider' => $dataProvider,
	'modal' => $modal,
]);?>