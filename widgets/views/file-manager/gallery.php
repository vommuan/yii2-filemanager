<?php

use vommuan\filemanager\Module;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

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
			'itemOptions' => ['tag' => false],
			'itemView' => function ($model, $key, $index, $widget) {
				return $this->render('gallery-items__item', [
					'model' => $model,
				]);
			},
		]);?>
	<?php Pjax::end();?>
</div>