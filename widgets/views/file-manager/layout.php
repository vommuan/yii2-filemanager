<?php

use vommuan\filemanager\assets\FileManagerAsset;
use vommuan\filemanager\Module;
use yii\helpers\Url;

/* @var $multiple string */
/* @var $this yii\web\View */
/* @var $widgetId string */

FileManagerAsset::register($this);

$baseUrl = Url::to(['/' . Module::getInstance()->uniqueId . '/file']);

?>

<div class="file-manager mode" data-base-url="<?= $baseUrl;?>">
	<div class="mode__block">
		<div class="file-manager__header header-bar">
			<div class="header-bar__title title">
				<div class="title__icon">
					<span class="glyphicon glyphicon-picture"></span>
				</div>
				
				<div class="title__text"><?= Module::t('main', 'Files');?></div>
			</div>
			<div class="header-bar__upload-form">
				<?= $this->render('_upload-form', [
					'widgetId' => $widgetId,
				]);?>
			</div>
			<div class="header-bar__close-button">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>

		<div class="file-manager__content">
			<?= $this->render('_gallery', [
				'widgetId' => $widgetId,
				'multiple' => $multiple,
			]);?>
			<div class="details-bar">
				<div class="file-details"></div>
				<div class="attache-button">
					<button class="btn insert-button">
						<?= Module::t('main', 'Save');?>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="mode__block mode__block_edit mode__block_hide"></div>
</div>
