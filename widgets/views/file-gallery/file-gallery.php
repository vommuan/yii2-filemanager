<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\assets\FileGalleryAsset;

$bundle = FileGalleryAsset::register($this);
?>
<div class="file-gallery" data-details-url="<?= Url::to(['details']);?>" data-multiple='false'>
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'layout' => $this->render('__layout/file-gallery__layout'),
		'itemOptions' => [
			'class' => 'col-xs-4 col-sm-2 gallery-items__item media-file',
		],
		'itemView' => function ($model, $key, $index, $widget) use ($bundle) {
			return $this->render('__item/gallery-items__item', [
				'model' => $model,
				'bundle' => $bundle,
			]);
		},
	]);?>
	<div class="file-gallery__checker">
		<span class="glyphicon glyphicon-check"></span>
	</div>
</div>