<?php
use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel vommuan\filemanager\models\MediaFile */
?>

<div class="page-header">
	<h1>
		<span class="glyphicon glyphicon-upload"></span>
		<?= Module::t('main', 'Upload manager');?>
	</h1>
</div>
<div class="row">
	<div class="col-xs-12">
		<?= FileUploadUI::widget([
			'model' => $model,
			'attribute' => 'file',
			'clientOptions' => [
				'autoUpload'=> Module::getInstance()->autoUpload,
			],
			'clientEvents' => [
				'fileuploadsubmit' => "function (e, data) {
					data.formData = [{
						name: 'tagIds',
						value: $('#filemanager-tagIds').val()
					}]; 
				}",
			],
			'url' => ['upload'],
			'gallery' => false,
		]);?>
	</div>
</div>
