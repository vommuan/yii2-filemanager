<?php
namespace vommuan\filemanager\models\handlers;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\base\ErrorException;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Thumbs;

class ImageHandler extends BaseHandler
{
	/**
	 * @var vommuan\filemanager\models\Thumbs
	 */
	protected $_thumbs;
	
	/**
	 * 
	 */
	protected function initThumbs()
	{
		$this->_thumbs = new Thumbs([
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
	 * 
	 */
	public function getIcon($baseUrl)
	{
		return $this->_thumbs->getDefault();
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
		return ArrayHelper::map($this->activeRecord->thumbnails, 'alias', 'url');
	}
	
	/**
	 * Append thumbnail files information to active record
	 */
	public function appendThumbs()
	{
		
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
	}
	
	/**
	 * 
	 */
	public function afterSave($insert)
	{
		if ($insert) {
			if (Module::getInstance()->thumbsAutoCreate) {
				$this->_thumbs->create();
			} else {
				$this->_thumbs->createOne('default');
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		if (parent::delete()) {
			return $this->_thumbs->delete();
		}
	}
	
	/**
	 * Get one variant of this file
	 * 
	 * @param string $alias alias of file variant
	 * @return string path to file
	 */
	public function getVariant($alias)
	{
		return $this->_thumbs->getUrl($alias);
	}
	
	/**
     * @param Module $module
     * @return array images list
     */
    public function getVariantsList()
    {
		$list = [
			[
				'alias' => 'origin',
				'label' => Module::t('main', 'Original') . ' ' . $this->getSizes(),
				'url' => $this->activeRecord->url,
			],
		];
		
		return array_merge($list, $this->_thumbs->getThumbsList());
	}
    
    /**
     * Get file width x height sizes
     * 
     * @param string $delimiter delimiter between width and height
     * @param string $format see [[Thumbs::getSizes()]] for detailed documentation
     * @return string image size like '1366x768'
     */
    public function getSizes($delimiter = 'x', $format = '{w}{d}{h}')
    {
        return $this->_thumbs->getSizes($this->absoluteFileName, $delimiter, $format);
    }
}