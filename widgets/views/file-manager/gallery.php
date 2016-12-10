<?php

use vommuan\filemanager\Module;
use yii\helpers\Url;
use yii\widgets\ListView;

$detailsUrl = Url::to(['/' . Module::getInstance()->uniqueId . '/file/details']);
$insertFilesLoad = Url::to(['/' . Module::getInstance()->uniqueId . '/file/insert-files-load']);
$nextPageFileUrl = Url::to(['/' . Module::getInstance()->uniqueId . '/file/next-page-file']);

$pagerParams = [
	'hideOnSinglePage' => false,
	'firstPageLabel' => '&#8676;',
	'prevPageLabel' => '&larr;',
	'nextPageLabel' => '&rarr;',
	'lastPageLabel' => '&#8677;',
];

$galleryItemsId = $modalId . '_gallery-items';

?>

<div class="col-xs-12 col-sm-8 gallery" data-details-url="<?= $detailsUrl;?>" data-insert-files-load="<?= $insertFilesLoad;?>" data-next-page-file-url="<?= $nextPageFileUrl;?>" data-multiple="<?= $multiple;?>">
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