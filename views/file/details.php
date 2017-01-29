<?php

use vommuan\filemanager\assets\FileGalleryAsset;
use vommuan\filemanager\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vommuan\filemanager\models\MediaFile */
/* @var $form yii\widgets\ActiveForm */

$bundle = FileGalleryAsset::register($this);
?>

<?php 
$form = ActiveForm::begin([
	'action' => [
		'update',
		'id' => $model->mediaFile->id,
	],
	'enableClientValidation' => false,
	'options' => [
		'class' => 'control-form file-details-form',
	],
]);?>
	<div class="row">
		<div class="col-xs-12">
			<?php
			if ('image' == $model->mediaFile->baseType) :?>
				<div class="cropper">
					<div class="thumbnail cropper__image-block">
						<?= Html::img($model->mediaFile->getFileVariant() . '?' . $model->mediaFile->updated_at, ['class' => 'crop-image']) ?>
					</div>
					<div class="cropper__control-block controls">
						<button class="btn btn-primary controls__rotate controls__rotate_left" title="<?= Module::t('main', 'Rotate left')?>">
							<span class="fa fa-rotate-left"></span>
						</button>
						<button class="btn btn-primary controls__rotate controls__rotate_right" title="<?= Module::t('main', 'Rotate right')?>">
							<span class="fa fa-rotate-right"></span>
						</button>
					</div>
					<?= $form->field($model, 'rotate')->hiddenInput(['class' => 'cropper__rotate-input'])->label(false);?>
				</div>
				<?php
			else :?>
				<div class="thumbnail">
					<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl) . '?' . $model->mediaFile->updated_at) ?>
				</div>
				<?php
			endif;?>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?php
			if ('image' == $model->mediaFile->baseType) {
				echo $form->field($model, 'alt')->textInput();
			}

			echo $form->field($model, 'description')->textarea();
			?>
			
			<?= Html::button(Module::t('main', 'Insert'), ['class' => 'btn btn-primary file-details-form__insert-button']);?>
			
			<?php
			if ('image' == $model->mediaFile->baseType) :?>
				<?= Html::button(Module::t('main', 'Edit'), [
					'class' => 'btn btn-default file-details-form__edit-button',
					'data' => [
						'key' => $model->mediaFile->id,
					],
				]);?>
				<?php
			endif;?>

			<?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success file-details-form__save-button']);?>
			
			<?= Html::a(
				Module::t('main', 'Delete'), [
					'delete',
					'id' => $model->mediaFile->id
				], [
					'class' => 'btn btn-danger file-details-form__delete-button',
					'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
				]
			);?>
			
			<?php 
			if ($message = Yii::$app->session->getFlash('mediaFileUpdateResult')) :?>
				<div class="text-success"><?= $message;?></div>
				<?php
			endif; ?>
		</div>
	</div>
<?php 
ActiveForm::end();?>