<?php

namespace vommuan\filemanager\widgets;

use vommuan\filemanager\models\MediaFileSearch;
use vommuan\filemanager\models\UploadFileForm;
use yii\base\Widget;

/**
 * File gallery
 */
class FileManager extends Widget
{
	public $parentWidgetId = '';
	
	public $multiple = 'false';
	
	public function run()
	{
		return $this->render('file-manager/layout', [
			'dataProvider' => (new MediaFileSearch())->search(),
			'modalId' => $this->parentWidgetId,
			'multiple' => $this->multiple,
			'uploadModel' => new UploadFileForm(),
		]);
	}
}