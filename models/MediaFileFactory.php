<?php
namespace vommuan\filemanager\models;

use yii\base\Model;

class MediaFileFactory extends Model
{
	/**
	 * Get array of image file types
	 * 
	 * @return array
	 */
	private static function getImageFileTypes()
	{
		return [
			'image/gif',
			'image/jpeg',
			'image/pjpeg',
			'image/png',
			'image/svg+xml',
			'image/tiff',
			'image/vnd.microsoft.icon',
			'image/vnd.wap.wbmp',
		];
	}
    
    /**
	 * If type of this media file is image, return true
	 * 
	 * @param string $fileType MIME-type of file
	 * @return boolean
	 */
	private static function isImage($fileType)
	{
		return in_array($fileType, self::getImageFileTypes());
	}
	
	/**
	 * Get handler to save file by type
	 * 
	 * @return mixed
	 */
	public function getMediaFile($file)
	{
		if (self::isImage($file->type)) {
			return new ImageFile(['file' => $file]);
		} else {
			return new MediaFile(['file' => $file]);
		}
	}
	
	/**
	 * Get one record from database and wrap it of handler class
	 */
	public function getOne($mediaFileId)
	{
		$ar = MediaFileAR::findOne($mediaFileId);
		
		if (self::isImage($ar->type)) {
			return new ImageFile(['activeRecord' => $ar]);
		} else {
			return new MediaFile(['activeRecord' => $ar]);
		}
	}
}