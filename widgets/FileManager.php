<?php

namespace vommuan\filemanager\widgets;

use yii\base\Widget;
use vommuan\filemanager\models\UploadFileForm;
use vommuan\filemanager\models\MediaFileSearch;

/**
 * File gallery
 */
class FileManager extends Widget
{
	public $modal = false;
	
	public function run()
	{
		return $this->render('file-manager/file-manager', [
			'uploadModel' => new UploadFileForm(),
			'dataProvider' => (new MediaFileSearch())->search(),
			'modal' => $this->modal,
		]);
	}
}