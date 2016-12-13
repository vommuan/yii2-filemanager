<?php

use vommuan\filemanager\assets\FileGalleryAsset;
use vommuan\filemanager\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $mediaFiles array of vommuan\filemanager\models\MediaFile */

$bundle = FileGalleryAsset::register($this);
?>

<?php 
foreach ($mediaFiles as $mediaFile) :?>
	<div class="selected-file">
		<?= Html::img(
			$mediaFile->getIcon($bundle->baseUrl), ArrayHelper::merge(
				[
					'alt' => $mediaFile->alt,
					'class' => 'selected-file__image',
					'data' => [
						'id' => $mediaFile->id,
					],
				],
				$imageOptions
			)
		);?>
	</div>
	<?php
endforeach;?>