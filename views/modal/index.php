<?php
use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\widgets\FileGallery;
use vommuan\filemanager\assets\ModalAsset;
use vommuan\filemanager\assets\FileManagerAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Files');

ModalAsset::register($this);
$this->params['moduleBundle'] = FileManagerAsset::register($this);
?>

<?= PageHeader::widget([
	'icon' => 'picture',
	'title' => $this->title,
]);?>

<div class="row">
	<div class="col-xs-12">
		<?= FileUploadUI::widget([
			'model' => $uploadModel,
			'attribute' => 'file',
			'clientOptions' => [
				'autoUpload' => true,
				'filesContainer' => '.gallery-items',
				'prependFiles' => true,
			],
			'clientEvents' => [
				'fileuploadstart' => 'function(event) {
					$(".file-gallery").find(".gallery-items__item:gt(' . (MediaFileSearch::PAGE_SIZE - 1) . ')").each(function() {
						$(this).fadeOut(function() {
							$(this).remove();
						})
					});
				}',
				'fileuploadcompleted' => 'function(event, data) {
					var gallery = $("[data-key=\'" + data.result.files[0].id + "\']").closest(".file-gallery");
					
					var galleryPager = new GalleryPager(gallery);
					var gallerySummary = new GallerySummary(gallery);
					
					galleryPager.update(data.result.files[0].pagination);
					gallerySummary.update(data.result.files[0].pagination);
				}',
			],
			'url' => ['upload'],
			'formView' => '/fileuploadui/form',
			'uploadTemplateView' => '/fileuploadui/upload',
			'downloadTemplateView' => '/fileuploadui/download',
			'gallery' => false,
		]);?>
	</div>
</div>

<?= FileGallery::widget([
	'dataProvider' => $dataProvider,
]);?>