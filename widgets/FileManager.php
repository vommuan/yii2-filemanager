<?php

namespace vommuan\filemanager\widgets;

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
			'multiple' => $this->multiple,
			'widgetId' => $this->id,
		]);
	}
}