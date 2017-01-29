<?php

use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use yii\helpers\Html;

/* @var $multiple string */
/* @var $this yii\web\View */
/* @var $widgetId string */

?>

<div class="file-manager__header">
	<?= PageHeader::widget([
		'icon' => 'pencil',
		'title' => Module::t('main', 'Edit'),
	]);?>
</div>

<div class="file-manager__content">
	<div class="cropper">
		<div class="cropper__control-block controls">
			<button class="btn btn-primary controls__rotate controls__rotate_left" title="<?= Module::t('main', 'Rotate left');?>">
				<span class="fa fa-rotate-left"></span>
			</button>
			<button class="btn btn-primary controls__rotate controls__rotate_right" title="<?= Module::t('main', 'Rotate right');?>">
				<span class="fa fa-rotate-right"></span>
			</button>
		</div>
		<div class="thumbnail cropper__image-block">
			<?= Html::img($mediaFile->getFileVariant() . '?' . $mediaFile->updated_at, ['class' => 'crop-image']);?>
		</div>
	</div>
	
	<div class="main-controls">
		<?= Html::button(Module::t('main', 'Cancel'), [
			'class' => 'btn btn-default main-controls__cancel-button',
			'data' => [
				'message' => Module::t('main', 'Are you sure you want to cancel changes?'),
			],
		]);?>
		
		<?= Html::button(Module::t('main', 'Save'), ['class' => 'btn btn-success main-controls__save-button']);?>
	</div>
</div>