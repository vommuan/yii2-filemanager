<?php
namespace vommuan\filemanager\models;

use yii\base\Model;
use yii\base\ErrorException;
use yii\imagine\Image;
use Imagine\Image\ImageInterface;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\handlers\ImageHandler;
use vommuan\filemanager\models\helpers\FileHelper;

/**
 * This is the helper model class for route paths
 */
class ImageThumbnail extends Model
{
    public $handler;
    private $_config;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_config = array_merge(Module::getInstance()->thumbs, Module::getInstance()->defaultThumbs);
        
        if (! ($this->handler instanceof ImageHandler)) {
            throw new ErrorException('Error class initialization.');
        }
    }
    
    /**
     * Get one thumbnail by alias
     * 
     * @return Thumbnail | null
     */
    protected function getThumbnail($alias)
	{
		return Thumbnail::findOne(['alias' => $alias, 'mediafile_id' => $this->handler->activeRecord->id]);
	}
    
    /**
     * Get thumbnails list
     * 
     * @return Thumbnail[]
     */
    protected function getThumbnails()
    {
		return Thumbnail::findAll(['mediafile_id' => $this->handler->activeRecord->id]);
	}
    
    /**
     * Generates thumb file name
     * 
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function generateFileName($width, $height) {
        return pathinfo($this->handler->activeRecord->filename, PATHINFO_FILENAME)
            . '-' . $width . 'x' . $height . '.'
            . pathinfo($this->handler->activeRecord->filename, PATHINFO_EXTENSION);
    }
    
    /**
     * Get configuration thumbnail sizes for alias
     * 
     * @param string $alias alias of thumbnail size
     * @return array
     * [
     *     0 => 300, // width in pixels
     *     1 => 200, // height in pixels
     * ]
     */
    protected function getAliasSizes($alias)
    {
		if (! isset($this->_config[$alias])) {
			return false;
		}
		
		if (! isset($this->_config[$alias]['size']) || 2 != count($this->_config[$alias]['size'])) {
			throw new ErrorException('Error. Wrong number of size parameters in thumbnail settings "' . $alias . '".');
		}
		
		return $this->_config[$alias]['size'];
	}
    
    /**
     * Create thumbnail file for this image
     * 
     * @param string $alias alias of thumbnail size
     * @return Thumbnail
     */
    public function createOne($alias)
    {
		if (false === ($sizes = $this->getAliasSizes($alias))) {
			return false;
		}
		
		FileHelper::createDirectory($this->handler->routes->getThumbsAbsolutePath(), 0777, true);
		
		list ($width, $height) = $sizes;
		
		Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];
		Image::thumbnail($this->handler->absoluteFileName, $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
			implode('/', [
				$this->handler->routes->getThumbsAbsolutePath(),
				$this->generateFileName($width, $height),
			])
		);
		
		$thumbnail = new Thumbnail([
			'alias' => $alias,
			'url' => implode('/', [
				$this->handler->routes->getThumbsUrlPath(),
				$this->generateFileName($width, $height),
			]),
			'mediafile_id' => $this->handler->activeRecord->id,
		]);
		
		return ($thumbnail->save()) ? $thumbnail : false;
	}

    /**
     * Create all thumbnails for this image
     */
    public function create()
    {
        foreach ($this->_config as $alias => $preset) {
            $this->createOne($alias);
        }
    }
    
    /**
     * @return string default thumbnail for image
     */
    public function getDefault()
    {
		return $this->getUrl('default');
    }
    
    /**
     * @param string $alias thumb alias
     * @return string thumb url
     */
    public function getUrl($alias)
    {
        if (null !== ($thumb = $this->getThumbnail($alias))) {
			return $thumb->url;
		}
		
		if (isset($this->_config[$alias]) && false !== ($thumb = $this->createOne($alias))) {
			return $thumb->url;
		}
        
        return false;
    }
    
    /**
     * Get absolute file name of this thumbnail alias
     * 
     * @param string $alias thumbnail alias
     * @return string
     */
    protected function getPath($alias)
    {
		return implode('/', [
			$this->handler->routes->basePath,
			$this->getUrl($alias),
		]);
	}

    /**
     * @param Module $module
     * @return array images list
     */
    public function getThumbsList()
    {
        $list = [];
        $thumbs = $this->getThumbnails();
        
        for ($i = 0; $i < count($thumbs); $i++) {
			$list[] = [
				'alias' => $thumbs[$i]->alias,
				'label' => $this->_config[$thumbs[$i]->alias]['name'] . ' ' . $this->getSizes($this->getPath($thumbs[$i]->alias)),
				'url' => $thumbs[$i]->url,
            ];
		}
        
        return $list;
    }
    
    /**
     * This method wrap getimagesize() function
     * 
     * @param string $filePath path to image file
     * @param string $delimiter delimiter between width and height
     * @param string $format output string format
     * - {w} replaced to width
     * - {d} replaced to delimiter
     * - {h} replaced to height
     * @return string image size like '1366x768'
     */
    public function getSizes($filePath, $delimiter = 'x', $format = '{w}{d}{h}')
    {
        $imageSizes = getimagesize($filePath);
        
        return str_replace(
			['{w}', '{d}', '{h}'], 
			[$imageSizes[0], $delimiter, $imageSizes[1]], 
			$format
		);
    }
	
	
	
    /**
     * Delete thumbnails for current image
     * 
     * @param array $routes see routes in module config
     */
    public function delete()
    {
        $thumbs = $this->getThumbnails();
        
        for ($i = 0; $i < count($thumbs); $i++) {
			unlink($this->handler->routes->basePath . '/' . $thumbs[$i]->url);
		}
        
        FileHelper::removeDirectory(
			$this->handler->routes->getThumbsAbsolutePath($this->handler->activeRecord->url), 
			['onlyEmpty' => true]
		);
        
        return true;
    }
}
