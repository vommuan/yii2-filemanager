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
		'class' => 'control-form details-form',
	],
]);?>
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="thumbnail pull-left">
				<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl) . '?' . $model->mediaFile->updated_at);?>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<?php
			if ('image' == $model->mediaFile->baseType) :?>
				<?= Html::a(
					'<span class="glyphicon glyphicon-pencil"></span>', [
						'edit',
						'id' => $model->mediaFile->id,
					], [
						'class' => 'btn btn-primary details-form__edit-link',
						'title' => Module::t('main', 'Edit'),
					]
				);?>
				<?php
			endif;?>
			
			<?= Html::a(
				'<span class="glyphicon glyphicon-remove"></span>', [
					'delete',
					'id' => $model->mediaFile->id,
				], [
					'class' => 'btn btn-danger details-form__delete-link',
					'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
					'title' => Module::t('main', 'Delete forever'),
				]
			);?>
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

			<?= Html::submitButton(Module::t('main', 'Save description'), ['class' => 'btn btn-success details-form__save-button']);?>
			
			<?php 
			if ($message = Yii::$app->session->getFlash('mediaFileUpdateResult')) :?>
				<div class="text-success"><?= $message;?></div>
				<?php
			endif;?>
		</div>
	</div>
<?php 
ActiveForm::end();?>