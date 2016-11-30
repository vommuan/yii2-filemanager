<?php

use yii\helpers\Html;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FileGalleryAsset;

/* @var $this yii\web\View */
/* @var $mediaFiles array of vommuan\filemanager\models\MediaFile */

$bundle = FileGalleryAsset::register($this);
?>

<?php 
foreach ($mediaFiles as $mediaFile) :?>
	<div class="selected-file">
		<?= Html::img(
			$mediaFile->getIcon($bundle->baseUrl), [
				'alt' => $mediaFile->alt,
				'class' => 'selected-file__image',
				'data' => [
					'id' => $mediaFile->id,
				],
			]
		);?>
	</div>
	<?php
endforeach;?>