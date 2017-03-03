<?php

use vommuan\filemanager\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
?>

<?php 
$form = ActiveForm::begin([
	'action' => [
		'edit',
		'id' => $model->mediaFile->id,
	],
	'enableClientValidation' => false,
	'options' => [
		'class' => 'control-form image-edit-form',
	],
]);?>

	<div class="file-manager__header header-bar">
		<div class="header-bar__title title">
			<div class="title__icon">
				<span class="glyphicon glyphicon-pencil"></span>
			</div>
			
			<div class="title__text"><?= Module::t('main', 'Edit');?></div>
		</div>
		<div class="header-bar__controls main-controls">
			<?= Html::button(Module::t('main', 'Cancel'), [
				'class' => 'main-controls__control main-controls__control_cancel controls-item',
			]);?>
			
			<?= Html::submitButton(
				Module::t('main', 'Save'),
				['class' => 'main-controls__control main-controls__control_save controls-item']
			);?>
		</div>
	</div>
	
	<div class="file-manager__content">
		<div class="cropper">
			<div class="cropper__image-block">
				<?= Html::img($model->mediaFile->getFileVariant() . '?' . $model->mediaFile->updated_at, ['class' => 'crop-image']);?>
			</div>
			
			<div class="cropper__control-block">
				<div class="pull-left controls">
					<button class="controls__rotate controls__rotate_left controls-item" title="<?= Module::t('main', 'Rotate left');?>">
						<span class="fa fa-rotate-left"></span>
					</button>
					<button class="controls__rotate controls__rotate_right controls-item" title="<?= Module::t('main', 'Rotate right');?>">
						<span class="fa fa-rotate-right"></span>
					</button>
				</div>
			</div>
			
			<?= $form->field($model, 'rotate')->hiddenInput(['class' => 'cropper__rotate-input'])->label(false);?>
			<?= $form->field($model, 'cropX')->hiddenInput(['class' => 'cropper__crop-x-input'])->label(false);?>
			<?= $form->field($model, 'cropY')->hiddenInput(['class' => 'cropper__crop-y-input'])->label(false);?>
			<?= $form->field($model, 'cropWidth')->hiddenInput(['class' => 'cropper__crop-width-input'])->label(false);?>
			<?= $form->field($model, 'cropHeight')->hiddenInput(['class' => 'cropper__crop-height-input'])->label(false);?>
		</div>
	</div>
	<?php 
ActiveForm::end();?>