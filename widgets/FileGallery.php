<?php

namespace vommuan\filemanager\widgets;

use yii\base\Widget;

/**
 * File gallery
 */
class FileGallery extends Widget
{
	public $dataProvider;
	public $modal;
	
	public function run()
	{
		return $this->render('file-gallery/file-gallery', [
			'dataProvider' => $this->dataProvider,
			'modal' => $this->modal,
		]);
	}
}