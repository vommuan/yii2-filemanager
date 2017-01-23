<?php
namespace vommuan\filemanager\models\handlers;

use vommuan\filemanager\models\ImageThumbnail;
use vommuan\filemanager\Module;
use Yii;
use yii\base\ErrorException;
use yii\imagine\Image;

/**
 * Image handler
 * 
 * @author Michael Naumov <vommuan@gmail.com>
 */
class ImageHandler extends BaseHandler
{
	/**
	 * @var vommuan\filemanager\models\ImageThumbnail
	 */
	protected $_imageThumbnail;
	
	/**
	 * Initialization [[ImageThumbnail]] object
	 */
	protected function initImageThumbnail()
	{
		$this->_imageThumbnail = new ImageThumbnail(['handler' => $this]);
	}
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->initImageThumbnail();
	}
	
	/**
	 * @inheritdoc
	 */
	public function getIcon($baseUrl)
	{
		return $this->getVariant('default');
	}
	
	/**
	 * Crop image into max sizes with saving proportions
	 * Array indexes: 0 - width, 1 - height
	 * 
	 * @return integer size of file
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
	 * Rotate image on specified angle
	 * 
	 * @return integer size of file
	 */
	protected function rotateImage()
	{
		$imagine = Image::getImagine();
		
		$imagine->open($this->absoluteFileName)->rotate($this->activeRecord->rotate)->save($this->absoluteFileName);
		
		$this->refreshFileVariants();
		
		return filesize($this->absoluteFileName);
	}
	
	/**
	 * @inheritdoc
	 */
	protected function afterFileSave()
	{
		if (isset(Module::getInstance()->maxImageSizes)) {
			$this->activeRecord->size = $this->cropImage();
		}
	}
	
	/**
	 * Function witch calling before active record MediaFile save
	 * 
	 * @return boolean
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if (isset($this->activeRecord->rotate) && 0 != $this->activeRecord->rotate) {
				$this->activeRecord->size = $this->rotateImage();
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function afterSave($insert)
	{
		if ($insert) {
			if (Module::getInstance()->thumbsAutoCreate) {
				$this->_imageThumbnail->createAll();
			} else {
				$this->_imageThumbnail->createOne('default');
			}
		}
	}
	
	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		if (parent::delete()) {
			$this->_imageThumbnail->delete();
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
		return Yii::getAlias('@web/' . $this->_imageThumbnail->getUrl($alias));
	}
	
	/**
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
		
		return array_merge($list, $this->_imageThumbnail->getList());
	}
	
	/**
	 * Remove old file's variants and generate new
	 */
	public function refreshFileVariants()
	{
		$this->_imageThumbnail->delete();
		
		if (Module::getInstance()->thumbsAutoCreate) {
			$this->_imageThumbnail->createAll();
		} else {
			$this->_imageThumbnail->createOne('default');
		}
	}
    
    /**
     * Get file width x height sizes
     * 
     * @param string $delimiter delimiter between width and height
     * @param string $format see [[ImageThumbnail::getSizes()]] for detailed documentation
     * @return string image size like '1366x768'
     */
    public function getSizes($delimiter = 'x', $format = '{w}{d}{h}')
    {
        return $this->_imageThumbnail->getSizes($this->absoluteFileName, $delimiter, $format);
    }
}