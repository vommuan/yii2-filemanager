<?php

use vommuan\filemanager\assets\CropImageAsset;
use vommuan\filemanager\assets\FileGalleryAsset;
use vommuan\filemanager\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model vommuan\filemanager\models\MediaFile */
/* @var $form yii\widgets\ActiveForm */

$bundle = FileGalleryAsset::register($this);
CropImageAsset::register($this);
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
						<?= Html::img($model->mediaFile->getFileVariant(), ['class' => 'crop-image']) ?>
					</div>
					<div class="cropper__control-block controls">
						<button class="btn btn-primary controls__rotate controls__rotate_left">Left</button>
						<button class="btn btn-primary controls__rotate controls__rotate_right">Right</button>
					</div>
				</div>
				<?php
			else :?>
				<div class="thumbnail">
					<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl)) ?>
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
			
			<?= Html::hiddenInput('url', $model->mediaFile->url);// What for? Is it legacy code? ?>

			<?= Html::hiddenInput('id', $model->mediaFile->id);// What for? Is it legacy code? ?>
			
			<?= Html::button(Module::t('main', 'Insert'), ['class' => 'btn btn-primary insert-btn file-details-form__insert-button']);?>

			<?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success file-details-form__save-button']);?>
			
			<?= Html::a(
				Module::t('main', 'Delete'), [
					'delete',
					'id' => $model->mediaFile->id
				], [
					'class' => 'btn btn-danger',
					'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
					'role' => 'delete',
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