<?php
namespace vommuan\filemanager\models;

use yii\base\Model;

/**
 * Form for upload files
 * 
 * @license MIT
 * @author Michael Naumov <vommuan@gmail.com>
 */
class UpdateFileForm extends Model
{
	/**
	 * @var MediaFile or one of children
	 */
	public $mediaFile;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['alt', 'description'], 'string'],
		];
	}
	
	/**
	 * Get alternative text for media file
	 * 
	 * @return string
	 */
	public function getAlt()
	{
		return $this->mediaFile->alt;
	}
	
	/**
	 * Get description for media file
	 * 
	 * @return string
	 */
	public function getDescription()
	{
		return $this->mediaFile->description;
	}
	
	/**
	 * Update information about file
	 */
	public function update()
	{
		if (!$this->validate()) {
			return false;
		}
		
        return $this->mediaFile->update($this->attributes);
	}
}