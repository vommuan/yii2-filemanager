<?php

use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

?>

<div class="file-manager__header">
	<?= PageHeader::widget([
		'icon' => 'pencil',
		'title' => Module::t('main', 'Edit'),
	]);?>
</div>

<div class="file-manager__content">
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
		<div class="cropper">
			<div class="cropper__control-block controls">
				<button class="btn btn-default controls__rotate controls__rotate_left" title="<?= Module::t('main', 'Rotate left');?>">
					<span class="fa fa-rotate-left"></span>
				</button>
				<button class="btn btn-default controls__rotate controls__rotate_right" title="<?= Module::t('main', 'Rotate right');?>">
					<span class="fa fa-rotate-right"></span>
				</button>
			</div>
			<div class="thumbnail cropper__image-block">
				<?= Html::img($model->mediaFile->getFileVariant() . '?' . $model->mediaFile->updated_at, ['class' => 'crop-image']);?>
			</div>
			<?= $form->field($model, 'rotate')->hiddenInput(['class' => 'cropper__rotate-input'])->label(false);?>
		</div>
		
		<div class="main-controls">
			<?= Html::button(Module::t('main', 'Cancel'), [
				'class' => 'btn btn-default main-controls__cancel-button',
				'data' => [
					'message' => Module::t('main', 'Are you sure you want to cancel changes?'),
				],
			]);?>
			
			<?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success main-controls__save-button']);?>
		</div>
		
		<?php 
		if ($message = Yii::$app->session->getFlash('imageEditResult')) :?>
			<div class="text-success"><?= $message;?></div>
			<?php
		endif;?>
		<?php 
	ActiveForm::end();?>
</div>