<?php

use yii\helpers\Html;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FileGalleryAsset;

/* @var $this yii\web\View */
/* @var $mediaFiles array of vommuan\filemanager\models\MediaFile */

$bundle = FileGalleryAsset::register($this);
?>

<?php 
for ($i = 0; $i < count($mediaFiles); $i++) :?>
	<?= Html::img(
		$mediaFiles[$i]->getIcon($bundle->baseUrl), [
			'alt' => $mediaFiles[$i]->alt,
			'class' => 'selected-image',
		]
	);?>
	<?php
endfor;?>