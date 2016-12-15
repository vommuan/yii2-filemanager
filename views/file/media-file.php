<?php

use vommuan\filemanager\assets\FileGalleryAsset;
use yii\helpers\Html;

$bundle = FileGalleryAsset::register($this);

?>

<div class="col-xs-4 col-sm-2 gallery-items__item media-file" data-key="<?= $model->id;?>">
	<a href="#" class="thumbnail media-file__link">
		<?= Html::img($model->getIcon($bundle->baseUrl) . '?' . $model->updated_at);?>
		<div class="checker">
			<span class="glyphicon glyphicon-check"></span>
		</div>
	</a>
</div>