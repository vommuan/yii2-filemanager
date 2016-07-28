<?php
use yii\helpers\Html;
use vommuan\filemanager\assets\FileManagerAsset;

$bundle = FileManagerAsset::register($this);
?>
<div class="col-xs-4 col-sm-2 gallery-items__item media-file" data-key="<?= $model->id;?>">
	<a href="#" class="thumbnail media-file__link">
		<?= Html::img($model->getIcon($bundle->baseUrl), ['class' => 'media-file__image']) ?>
	</a>
</div>