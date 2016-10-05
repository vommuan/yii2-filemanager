<?php
use yii\helpers\Html;
use vommuan\filemanager\assets\FileGalleryAsset;

$bundle = FileGalleryAsset::register($this);
?>
<div class="col-xs-4 col-sm-2 gallery-items__item media-file" data-key="<?= $model->id;?>">
	<a href="#" class="thumbnail media-file__link">
		<?= Html::img($model->getIcon($bundle->baseUrl), ['class' => 'media-file__image']) ?>
		<div class="file-gallery__checker">
			<span class="glyphicon glyphicon-check"></span>
		</div>
	</a>
</div>