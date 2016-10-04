<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
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
			'details-target' => '#file-info_' . $modalId,
			'multiple' => $multiple,
		],
	]
);?>
	<div class="row file-gallery__layout">
		<?php Pjax::begin([
			'linkSelector' => (!empty($modalId) ? '#' . $modalId . ' ' : '') . '.pagination a',
		]);?>
			<div class="col-xs-12 col-sm-8">
				<?= ListView::widget([
					'dataProvider' => $dataProvider,
					'emptyText' => $this->render('file-gallery__empty-text'),
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
			</div>
		<?php Pjax::end();?>
		<div class="col-xs-12 col-sm-4" id="<?= 'file-info_' . $modalId;?>" class="file-info"></div>
	</div>
	<div class="file-gallery__checker">
		<span class="glyphicon glyphicon-check"></span>
	</div>
<?= Html::endTag('div');?>