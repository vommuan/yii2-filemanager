<?php
namespace vommuan\filemanager\models\handlers;

use yii\base\Model;
use yii\imagine\Image;
use yii\base\ErrorException;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Thumbs;

class ImageHandler extends BaseHandler
{
	/**
	 * 
	 */
	protected $_thumbFiles;
	
	/**
	 * 
	 */
	protected function initThumbs()
	{
		$this->_thumbFiles = new Thumbs([
			'mediaFile' => $this,
		]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->initThumbs();
	}
	
	/**
     * Get access for readonly Thumbs object
     * 
     * @return vommuan\filemanager\models\Thumbs
     */
    public function getThumbFiles()
    {
        return $this->_thumbFiles;
    }
	
	/**
	 * 
	 */
	public function getIcon($baseUrl)
	{
		return $this->_thumbFiles->getDefault();
	}
	
	/**
	 * 
	 */
	public function getAlt()
	{
		return $this->activeRecord->alt;
	}
	
	/**
	 * Get thumbs information from active record
	 * 
	 * @return array
	 */
	public function getThumbs()
	{
		return unserialize($this->activeRecord->thumbs);
	}
	
	/**
	 * Set thumbs infomation to active record
	 * 
	 * @param array $thumbs
	 */
	public function setThumbs($thumbs)
	{
		$this->activeRecord->thumbs = serialize($thumbs);
	}
	
	/**
	 * Crop image into max sizes with saving proportions
	 * Array indexes: 0 - width, 1 - height
	 * 
	 * @return integer
	 * @throws yii\base\ErrorException if setting `Module::maxImageSizes` has error
	 */
	protected function cropImage()
	{
		$maxSizes = Module::getInstance()->maxImageSizes;
		$ignoreRotate = Module::getInstance()->ignoreImageRotate;
		
		if (! is_array($maxSizes) || count($maxSizes) != 2) {
			throw new ErrorException('Error module "vommuan\\filemanager\\' . Module::className() . '" settings: maxImageSizes');
		}
		
		$originSizes = array_slice(getimagesize($this->absoluteFileName), 0, 2);
		
		if ($ignoreRotate) {
			$isVertical = ($originSizes[0] < $originSizes[1]) ? true : false;
			sort($originSizes, SORT_NUMERIC);
			sort($maxSizes, SORT_NUMERIC);
		}
		
		// if original image sizes less or equal than max image sizes in settings
		if ($originSizes[0] <= $maxSizes[0] && $originSizes[1] <= $maxSizes[1]) {
			return filesize($this->absoluteFileName);
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
		
		Image::thumbnail($this->absoluteFileName, round($newSizes[0]), round($newSizes[1]))->save($this->absoluteFileName);
		
		return filesize($this->absoluteFileName);
	}
	
	/**
	 * 
	 */
	protected function afterFileSave()
	{
		if (isset(Module::getInstance()->maxImageSizes)) {
			$this->activeRecord->size = $this->cropImage();
		} else {
			$this->activeRecord->size = $this->activeRecord->file->size;
		}
		
		if (Module::getInstance()->thumbsAutoCreate) {
			$this->_thumbFiles->create();
		}
	}
	
	/**
     * @param Module $module
     * @return array images list
     */
    public function getImagesList()
    {
        $list = [
			$this->getUrl() => Module::t('main', 'Original') . ' ' . $this->getOriginalImageSize(),
		];
		
		return array_merge($list, $this->_thumbFiles->getThumbsList());
    }
    
    /**
     * This method wrap getimagesize() function
     * 
     * @param string $delimiter delimiter between width and height
     * @return string image size like '1366x768'
     */
    public function getOriginalImageSize($delimiter = 'x')
    {
        $imageSizes = getimagesize($this->absoluteFileName);
        
        return implode($delimiter, [
            $imageSizes[0],
            $imageSizes[1],
        ]);
    }
	
	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		if (parent::delete()) {
			return $this->_thumbFiles->delete();
		}
	}
}