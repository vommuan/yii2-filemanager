<?php
namespace vommuan\filemanager\models\handlers;

use yii\base\Model;

class HandlerFactory extends Model
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
	public static function getHandler($activeRecord)
	{
		if (self::isImage($activeRecord->type)) {
			return new ImageHandler(['activeRecord' => $activeRecord]);
		} else {
			return new BaseHandler(['activeRecord' => $activeRecord]);
		}
	}
}