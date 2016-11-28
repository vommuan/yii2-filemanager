<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\assets\FileManagerAsset;
use vommuan\filemanager\assets\FileGalleryAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['moduleBundle'] = FileManagerAsset::register($this);
$bundle = FileGalleryAsset::register($this);

?>

<?= PageHeader::widget([
	'icon' => 'picture',
	'title' => Module::t('main', 'Files'),
]);?>

<div class="row">
	<div class="col-xs-12">
		<?= $this->render('file-manager__upload-form', [
			'uploadModel' => $uploadModel,
			'modalId' => $modalId,
		]);?>
	</div>
</div>

<?= Html::beginTag(
	'div',
	[
		'class' => 'file-gallery',
		'data' => [
			'details-url' => Url::to([
				'/' . Module::getInstance()->uniqueId . '/file/details',
				'modal' => $modal,
			]),
			'details-target' => '#file-info_' . $modalId,
			'insert-files-load' => Url::to([
				'/' . Module::getInstance()->uniqueId . '/file/insert-files-load',
			]),
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
					'emptyText' => $this->render('file-manager__empty-text', ['modalId' => $modalId]),
					'layout' => $this->render('file-manager__layout', ['modalId' => $modalId]),
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
		<div class="col-xs-12 col-sm-4 file-info" id="<?= 'file-info_' . $modalId;?>"></div>
	</div>
<?= Html::endTag('div');?>