<?php

use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\MediaFileSearch;

?>

<?= FileUploadUI::widget([
	'model' => $uploadModel,
	'attribute' => 'file',
	'options' => [
		'id' => (!empty($modalId) ? $modalId . '-' : '') . 'fileupload',
	],
	'clientOptions' => [
		'autoUpload' => true,
		'filesContainer' => !empty($modalId) ? '#' . $modalId . '_gallery-items' : '.gallery-items',
		'prependFiles' => true,
	],
	'clientEvents' => [
		'fileuploadstart' => 'function(event) {
			$(".gallery").find(".gallery-items__item:gt(' . (MediaFileSearch::PAGE_SIZE - 1) . ')").each(function() {
				$(this).fadeOut(function() {
					$(this).remove();
				})
			});
		}',
		'fileuploadcompleted' => 'function(event, data) {
			var gallery = $("[data-key=\'" + data.result.files[0].id + "\']").closest(".gallery");
			
			var pager = (new GalleryPager()).init(gallery);
			pager.update(data.result.files[0].pagination);
			(new GallerySummary()).init(gallery, pager).update(data.result.files[0].pagination);
		}',
	],
	'url' => ['/' . Module::getInstance()->uniqueId . '/file/upload'],
	'formView' => '@filemanager/views/fileuploadui/form',
	'uploadTemplateView' => '@filemanager/views/fileuploadui/upload',
	'downloadTemplateView' => '@filemanager/views/fileuploadui/download',
	'gallery' => false,
]);?>