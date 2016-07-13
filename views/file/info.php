<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vommuan\filemanager\assets\FilemanagerAsset;
use vommuan\filemanager\Module;

/* @var $this yii\web\View */
/* @var $model vommuan\filemanager\models\MediaFile */
/* @var $form yii\widgets\ActiveForm */

$bundle = FilemanagerAsset::register($this);
?>

<div class="row">
	<div class="col-xs-12 col-sm-2">
		<div class="thumbnail">
			<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl)) ?>
		</div>
	</div>
	<div class="col-xs-12 col-sm-10">
		<ul class="detail">
			<li><?= $model->mediaFile->filename;?></li>
			<li>
				<?php 
				echo $model->mediaFile->type;
				
				if ('image' == $model->mediaFile->baseType) {
					echo ', ' . $model->mediaFile->sizes;
				}
				?>
			</li>
			<li><?= Yii::$app->formatter->asDate($model->mediaFile->getLastChanges());?></li>
			<li><?= $model->mediaFile->fileSize;?></li>
			<li>
				<?= Html::a(
					Module::t('main', 'Delete'), [
						'file/delete/', 
						'id' => $model->mediaFile->id
					], [
						'class' => 'text-danger',
						'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
						'data-id' => $model->mediaFile->id,
						'role' => 'delete',
					]
				);?>
			</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<?php 
		$form = ActiveForm::begin([
			'action' => [
				'file/update',
				'id' => $model->mediaFile->id,
			],
			'enableClientValidation' => false,
			'options' => [
				'id' => 'control-form',
			],
		]);
			
			if ('image' == $model->mediaFile->baseType) {
				echo $form->field($model, 'alt')->textInput(['class' => 'form-control']);
			}

			echo $form->field($model, 'description')->textarea(['class' => 'form-control']);
			 
			if ('image' == $model->mediaFile->baseType) :?>
				<div class="form-group<?= $strictThumb ? ' hidden' : '';?>">
					<?= Html::label(Module::t('main', 'Select image size'), 'image', ['class' => 'control-label']);?>

					<?= Html::dropDownList(
						'url',
						$model->mediaFile->getFileVariant($strictThumb),
						$model->mediaFile->getFileVariants(true), [
							'class' => 'form-control'
						]
					);?>
					<div class="help-block"></div>
				</div>
				<?php 
			else :?>
				<?= Html::hiddenInput('url', $model->mediaFile->url);?>
				<?php
			endif;?>

			<?= Html::hiddenInput('id', $model->mediaFile->id);?>

			<?= Html::button(Module::t('main', 'Insert'), ['id' => 'insert-btn', 'class' => 'btn btn-primary']);?>

			<?= Html::submitButton(Module::t('main', 'Save'), ['class' => 'btn btn-success']);?>

			<?php 
			if ($message = Yii::$app->session->getFlash('mediafileUpdateResult')) :?>
				<div class="text-success"><?= $message;?></div>
				<?php
			endif; ?>
			<?php 
		ActiveForm::end();?>
	</div>
</div>
