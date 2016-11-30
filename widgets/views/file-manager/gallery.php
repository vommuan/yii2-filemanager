<?php

use vommuan\filemanager\Module;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

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

<div class="col-xs-12 col-sm-8 gallery" data-details-url="<?= $detailsUrl;?>" data-insert-files-load="<?= $insertFilesLoad;?>" data-multiple="<?= $multiple;?>">
	<?php Pjax::begin([
		'linkSelector' => (!empty($modalId) ? '#' . $modalId . ' ' : '') . '.pagination a',
	]);?>
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'emptyText' => $this->render('gallery__empty-text', [
				'pagerParams' => $pagerParams,
				'galleryItemsId' => $galleryItemsId,
				'nextPageFileUrl' => $nextPageFileUrl,
			]),
			'layout' => $this->render('gallery__layout', [
				'galleryItemsId' => $galleryItemsId,
				'nextPageFileUrl' => $nextPageFileUrl,
			]),
			'pager' => $pagerParams,
			'itemOptions' => ['tag' => false],
			'itemView' => function ($model, $key, $index, $widget) {
				return $this->render('@filemanager/views/file/media-file', [
					'model' => $model,
				]);
			},
		]);?>
	<?php Pjax::end();?>
</div>