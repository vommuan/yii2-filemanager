<?php
namespace vommuan\filemanager\models\helpers;

/**
 * @author Michael Naumov <vommuan@gmail.com>
 */
class FileHelper extends \yii\helpers\FileHelper
{
	public static function directoryIsEmpty($dir)
	{
		if (!is_dir($dir)) {
			return true;
		}
		
		return (2 == count(scandir($dir))) ? true : false;
	}
	
	/**
	 * Additional parameter `$options['onlyEmpty']`. If `true`, function removes only empty directory
	 * 
	 * @inheritdoc
	 */
	public static function removeDirectory($dir, $options = [])
	{
		if (isset($options['onlyEmpty']) && $options['onlyEmpty'] && !self::directoryIsEmpty($dir)) {
			return;
		}
		
		parent::removeDirectory($dir, $options);
	}
}