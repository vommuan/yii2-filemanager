<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vommuan\filemanager\assets\FileGalleryAsset;
use vommuan\filemanager\Module;

/* @var $this yii\web\View */
/* @var $model vommuan\filemanager\models\MediaFile */
/* @var $form yii\widgets\ActiveForm */

$bundle = FileGalleryAsset::register($this);
?>

<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="thumbnail">
			<?= Html::img($model->mediaFile->getIcon($bundle->baseUrl)) ?>
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
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
						'delete',
						'id' => $model->mediaFile->id
					], [
						'class' => 'text-danger',
						'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
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
				'update',
				'id' => $model->mediaFile->id,
			],
			'enableClientValidation' => false,
			'options' => [
				'class' => 'control-form',
			],
		]);
			
			if ('image' == $model->mediaFile->baseType) {
				echo $form->field($model, 'alt')->textInput(['class' => 'form-control']);
			}

			echo $form->field($model, 'description')->textarea(['class' => 'form-control']);
			?>
			
			<?= Html::hiddenInput('url', $model->mediaFile->url);?>

			<?= Html::hiddenInput('id', $model->mediaFile->id);?>
			
			<?= Html::button(Module::t('main', 'Insert'), ['class' => 'btn btn-primary insert-btn']);?>

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
