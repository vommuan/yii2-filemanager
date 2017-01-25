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
	/**
	 * @var string 
	 */
	public $multiple = 'false';
	
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('file-manager/layout', [
			'dataProvider' => (new MediaFileSearch())->search(),
			'widgetId' => $this->id,
			'multiple' => $this->multiple,
			'uploadModel' => new UploadFileForm(),
		]);
	}
}