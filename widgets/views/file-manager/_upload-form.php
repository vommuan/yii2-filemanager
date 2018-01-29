<?php

use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\models\forms\UploadFileForm;
use vommuan\filemanager\Module;

?>

<?= FileUploadUI::widget([
	'model' => new UploadFileForm(),
	'attribute' => 'file',
	'options' => [
		'id' => $widgetId . '-fileupload',
	],
	'clientOptions' => [
		'autoUpload' => true,
		'filesContainer' => '#' . $widgetId . '_gallery-items',
		'prependFiles' => true,
	],
	'clientEvents' => [
		'fileuploadadd' => 'function(event, data) {
			var manager = $(event.currentTarget).closest(".file-manager"),
				pager = new GalleryPager().init(manager.find(".gallery").eq(0));
			
			data.url = manager.data("base-url") + "/upload&page=" + pager.getCurrentPage();
		}',
		'fileuploadstart' => 'function(event) {
			$(".gallery").find(".gallery-items__item:gt(' . (MediaFileSearch::PAGE_SIZE - 1) . ')").each(function() {
				$(this).fadeOut(function() {
					$(this).remove();
				})
			});
		}',
		'fileuploadcompleted' => 'function(event, data) {
			var gallery = $("[data-key=\'" + data.result.files[0].id + "\']").closest(".gallery");
			
			var pager = new GalleryPager().init(gallery);
			pager.update(data.result.files[0].pagination);
			new GallerySummary().init(gallery, pager).update(data.result.files[0].pagination);
		}',
	],
	'url' => ['/' . Module::getInstance()->uniqueId . '/file/upload'],
	'formView' => '@filemanager/views/fileuploadui/form',
	'uploadTemplateView' => '@filemanager/views/fileuploadui/upload',
	'downloadTemplateView' => '@filemanager/views/fileuploadui/download',
	'gallery' => false,
]);?>
