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

<div class="thumbnail-block">
	<div class="thumbnail-block__image">
		<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl) . '?' . $model->mediaFile->updated_at);?>
	</div>
	<div class="thumbnail-block__controls controls">
		<div class="controls__column">
			<?php
			if ('image' == $model->mediaFile->baseType) :?>
				<?= Html::a(
					'<span class="glyphicon glyphicon-pencil"></span>', [
						'edit',
						'id' => $model->mediaFile->id,
					], [
						'class' => 'controls-item controls-item_main controls-item_edit',
						'title' => Module::t('main', 'Edit'),
					]
				);?>
				<?php
			endif;?>
		</div>
		<div class="controls__column">
			<?= Html::a(
				'<span class="glyphicon glyphicon-remove"></span>', [
					'delete',
					'id' => $model->mediaFile->id,
				], [
					'class' => 'controls-item controls-item_main controls-item_delete',
					'data-message' => Yii::t('yii', 'Are you sure you want to delete this file?'),
					'title' => Module::t('main', 'Delete forever'),
				]
			);?>
		</div>
	</div>
</div>

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
	<?php
	if ('image' == $model->mediaFile->baseType) : ?>
		<?= $form->field($model, 'alt', [
			'template' => $this->render('_descriptionField'),
		])->textarea(['maxlength' => true]); // Label: "Description" ?>
		<?php
	endif; ?>

	<?php // echo $form->field($model, 'description')->textarea(); ?>
<?php 
ActiveForm::end();?>