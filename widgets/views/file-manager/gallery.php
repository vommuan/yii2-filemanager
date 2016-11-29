<?php

use vommuan\filemanager\assets\FileGalleryAsset;
use vommuan\filemanager\Module;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$bundle = FileGalleryAsset::register($this);

$detailsUrl = Url::to([
	'/' . Module::getInstance()->uniqueId . '/file/details',
	'modal' => $modal,
]);

$insertFilesLoad = Url::to(['/' . Module::getInstance()->uniqueId . '/file/insert-files-load']);

$pagerParams = [
	'hideOnSinglePage' => false,
	'firstPageLabel' => '&#8676;',
	'prevPageLabel' => '&larr;',
	'nextPageLabel' => '&rarr;',
	'lastPageLabel' => '&#8677;',
];

?>

<div class="col-xs-12 col-sm-8 gallery" data-details-url="<?= $detailsUrl;?>" data-insert-files-load="<?= $insertFilesLoad;?>" data-multiple="<?= $multiple;?>">
	<?php Pjax::begin([
		'linkSelector' => (!empty($modalId) ? '#' . $modalId . ' ' : '') . '.pagination a',
	]);?>
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'emptyText' => $this->render('gallery__empty-text', [
				'modalId' => $modalId,
				'pagerParams' => $pagerParams,
			]),
			'layout' => $this->render('gallery__layout', ['modalId' => $modalId]),
			'pager' => $pagerParams,
			'itemOptions' => [
				'class' => 'col-xs-4 col-sm-2 gallery-items__item media-file',
			],
			'itemView' => function ($model, $key, $index, $widget) use ($bundle) {
				return $this->render('gallery-items__item', [
					'model' => $model,
					'bundle' => $bundle,
				]);
			},
		]);?>
	<?php Pjax::end();?>
</div>