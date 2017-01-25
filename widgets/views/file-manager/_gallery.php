<?php

use vommuan\filemanager\Module;
use yii\helpers\Url;
use yii\widgets\ListView;

$baseUrl = Url::to(['/' . Module::getInstance()->uniqueId . '/file']);

$pagerParams = [
	'hideOnSinglePage' => false,
	'firstPageLabel' => '&#8676;',
	'prevPageLabel' => '&larr;',
	'nextPageLabel' => '&rarr;',
	'lastPageLabel' => '&#8677;',
];

$galleryItemsId = $widgetId . '_gallery-items';

?>

<div class="gallery" data-base-url="<?= $baseUrl;?>" data-multiple="<?= $multiple;?>">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'emptyText' => $this->render('gallery__empty-text', [
			'pagerParams' => $pagerParams,
			'galleryItemsId' => $galleryItemsId,
		]),
		'layout' => $this->render('gallery__layout', [
			'galleryItemsId' => $galleryItemsId,
		]),
		'pager' => $pagerParams,
		'itemOptions' => ['tag' => false],
		'itemView' => function ($model, $key, $index, $widget) {
			return $this->render('@filemanager/views/file/media-file', [
				'model' => $model,
			]);
		},
	]);?>
</div>