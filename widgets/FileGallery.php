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
	
	public $multiple;
	
	public function run()
	{
		return $this->render('file-gallery/file-gallery', [
			'dataProvider' => $this->dataProvider,
			'modal' => $this->modal,
			'multiple' => $this->multiple,
		]);
	}
}