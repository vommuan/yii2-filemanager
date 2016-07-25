<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\widgets\FileGallery;
use vommuan\filemanager\assets\ModalAsset;
use vommuan\filemanager\assets\FilemanagerAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Files');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'File manager'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

ModalAsset::register($this);
$this->params['moduleBundle'] = FilemanagerAsset::register($this);
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
				'fileuploadcompleted' => 'function(event, data) {
					$("[data-key=\'" + data.result.files[0].id + "\'] .media-file__link").on("click", mediaFileLinkClick);
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