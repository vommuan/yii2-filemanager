<?php

use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\Module;
use yii\widgets\ListView;

$pagerParams = [
	'hideOnSinglePage' => false,
	'firstPageLabel' => '&#8676;',
	'prevPageLabel' => '&larr;',
	'nextPageLabel' => '&rarr;',
	'lastPageLabel' => '&#8677;',
];

$galleryItemsId = $widgetId . '_gallery-items';

?>

<div class="gallery" data-multiple="<?= $multiple;?>">
	<?= ListView::widget([
		'dataProvider' => (new MediaFileSearch())->search(),
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