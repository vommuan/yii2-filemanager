<?php

namespace vommuan\filemanager\models\forms;

use yii\base\Model;
use vommuan\filemanager\Module;

/**
 * Form for edit image. Rotating, cropping and others.
 * 
 * @license MIT
 * @author Michael Naumov <vommuan@gmail.com>
 */
class EditImageForm extends Model
{
	/**
	 * @var MediaFile
	 */
	public $mediaFile;
	
	/**
	 * @var integer Rotate angle for images
	 */
	public $rotate = 0;
	
	/**
	 * @var double X coordinate for left high corner of cropping area
	 */
	public $cropX = 0;
	
	/**
	 * @var double Y coordinate for left high corner of cropping area
	 */
	public $cropY = 0;
	
	/**
	 * @var double Width of cropping area
	 */
	public $cropWidth;
	
	/**
	 * @var double Height of cropping area
	 */
	public $cropHeight;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['rotate'], 'integer', 'min' => -360, 'max' => 360],
			[['cropX', 'cropY'], 'number'],
			[['cropWidth', 'cropHeight'], 'number', 'min' => 1, 'message' => Module::t('main', 'Can\'t to save so small image.')],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function beforeValidate()
	{
		if (!parent::beforeValidate()) {
			return false;
		}
		
		if ($this->cropX < 0) {
			$this->cropX = 0;
		}
		
		if ($this->cropY < 0) {
			$this->cropY = 0;
		}
		
		return true;
	}
	
	/**
	 * Update information about file
	 */
	public function edit()
	{
		if (!$this->validate()) {
			return false;
		}
		
		$this->mediaFile->rotate = $this->rotate;
		$this->mediaFile->cropX = $this->cropX;
		$this->mediaFile->cropY = $this->cropY;
		$this->mediaFile->cropWidth = $this->cropWidth;
		$this->mediaFile->cropHeight = $this->cropHeight;
		
        return $this->mediaFile->save();
	}
}