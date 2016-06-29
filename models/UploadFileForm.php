<?php
namespace vommuan\filemanager\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Form for upload files
 * 
 * @license MIT
 * @author Michael Naumov <vommuan@gmail.com>
 */
class UploadFileForm extends Model
{
	public $file;
	
	protected $imageFileTypes = [
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/svg+xml',
        'image/tiff',
        'image/vnd.microsoft.icon',
        'image/vnd.wap.wbmp',
    ];
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['file'], 'required'],
			[['file'], 'file'],
		];
	}
	
	/**
	 * If type of this media file is image, return true
	 * 
	 * @param string $fileType MIME-type of file
	 * @return boolean
	 */
	protected function isImage($fileType)
	{
		return in_array($fileType, $this->imageFileTypes);
	}
	
	/**
	 * Get handler to save file by type
	 * 
	 * @return mixed
	 */
	public function getHandler()
	{
		$this->file = UploadedFile::getInstance($this, 'file');
		
		if ($this->isImage($this->file->type)) {
			return new ImageFile(['file' => $this->file]);
		} else {
			return new MediaFile(['file' => $this->file]);
		}
	}
}