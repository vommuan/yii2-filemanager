<?php

namespace vommuan\filemanager\models\forms;

use yii\base\Model;

/**
 * Form for update file information
 * 
 * @license MIT
 * @author Michael Naumov <vommuan@gmail.com>
 */
class UpdateFileForm extends Model
{
	/**
	 * @var vommuan\filemanager\models\MediaFile
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
			[['alt'], 'string', 'max' => 200],
			[['description'], 'string', 'max' => 1000],
		];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alt' => $this->mediaFile->getAttributeLabel('alt'),
            'description' => $this->mediaFile->getAttributeLabel('description'),
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
		}
		
		return $this->mediaFile->alt;
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
		}
		
		return $this->mediaFile->description;
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