<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\FileGalleryAsset;

$bundle = FileGalleryAsset::register($this);
?>

<?= Html::beginTag(
	'div',
	[
		'class' => 'file-gallery',
		'data' => [
			'details-url' => Url::to([
				'/' . Module::getInstance()->uniqueId . '/file/details',
				'modal' => $modal
			]),
			'multiple' => 'false',
		],
	]
);?>
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'layout' => $this->render('file-gallery__layout'),
		'pager' => [
			'hideOnSinglePage' => false,
			'firstPageLabel' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-fast-backward']),
			'prevPageLabel' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-backward']),
			'nextPageLabel' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-forward']),
			'lastPageLabel' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-fast-forward']),
		],
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
	<div class="file-gallery__checker">
		<span class="glyphicon glyphicon-check"></span>
	</div>
<?= Html::endTag('div');?>