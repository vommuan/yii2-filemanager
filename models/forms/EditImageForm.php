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
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['rotate'], 'integer', 'min' => -360, 'max' => 360],
		];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
        ];
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
		
        return $this->mediaFile->save();
	}
}