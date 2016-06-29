<?php
namespace vommuan\filemanager\models;

use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use vommuan\filemanager\Module;

/**
 * Class for working with image files
 * 
 * @since v0.3
 * @license MIT
 * @author Michael Naumov <vommuan@gmail.com>
 */
class ImageFile extends MediaFile
{
	/**
	 * Crop image into max sizes with saving proportions
	 * Array indexes: 0 - width, 1 - height
	 * 
	 * @return void
	 */
	protected function cropImage()
	{
		$maxSizes = Module::getInstance()->maxImageSizes;
		$ignoreRotate = Module::getInstance()->ignoreImageRotate;
		
		if (! is_array($maxSizes) || count($maxSizes) != 2) {
			throw new ErrorException('Error module "vommuan\\filemanager\\' . Module::className() . '" settings: maxImageSizes');
		}
		
		$fileName = implode('/', [
			$this->_routes->basePath,
			$this->url,
		]);
		
		$originSizes = array_slice(getimagesize($fileName), 0, 2);
		
		if ($ignoreRotate) {
			$isVertical = ($originSizes[0] < $originSizes[1]) ? true : false;
			sort($originSizes, SORT_NUMERIC);
			sort($maxSizes, SORT_NUMERIC);
		}
		
		// if original image sizes less or equal than max image sizes in settings
		if ($originSizes[0] <= $maxSizes[0] && $originSizes[1] <= $maxSizes[1]) {
			return;
		}
		
		$newSizes = [];
		
		$originProportions = $originSizes[0] / $originSizes[1];
		$newSizes[0] = $maxSizes[0];
		$newSizes[1] = $newSizes[0] / $originProportions;
		
		if ($maxSizes[1] < $newSizes[1]) {
			$newSizes[1] = $maxSizes[1];
			$newSizes[0] = $newSizes[1] * $originProportions;
		}
		
		if ($ignoreRotate && ! $isVertical) {
			rsort($newSizes);
		}
		
		Image::thumbnail($fileName, round($newSizes[0]), round($newSizes[1]))->save($fileName);
		
		$this->size = filesize($fileName);
		$this->save();
	}
	
	/**
	 * Save just uploaded file
	 * 
	 * @return bool
	 */
	public function saveUploadedFile()
	{
		if (false === $this->fileSave()) {
			return false;
		}
		
		$this->dbSave();
		
		if (isset(Module::getInstance()->maxImageSizes)) {
			$this->cropImage();
		}
		
		if (Module::getInstance()->thumbsAutoCreate) {
			$this->_thumbFiles->create();
		}
	}
}