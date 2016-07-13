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
	 * @var string
	 */
	private $_alt;
	
	/**
	 * @var string
	 */
	private $_description;
	
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
		if (isset($this->_alt)) {
			return $this->_alt;
		} else {
			return $this->mediaFile->alt;
		}
	}
	
	/**
	 * Get description for media file
	 * 
	 * @return string
	 */
	public function getDescription()
	{
		if (isset($this->_description)) {
			return $this->_description;
		} else {
			return $this->mediaFile->description;
		}
	}
	
	/**
	 * Set alternative text for media file
	 * 
	 * @return void
	 */
	public function setAlt($alt)
	{
		$this->_alt = $alt;
	}
	
	/**
	 * Set description for media file
	 * 
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->_description = $description;
	}
	
	/**
	 * Update information about file
	 */
	public function update()
	{
		if (!$this->validate()) {
			return false;
		}
		
		$this->mediaFile->alt = $this->_alt;
		$this->mediaFile->description = $this->_description;
		
        return $this->mediaFile->save();
	}
}