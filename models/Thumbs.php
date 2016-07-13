<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\handlers\ImageHandler;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\ImageInterface;

/**
 * This is the helper model class for route paths
 */
class Thumbs extends Model
{
    public $mediaFile;
    private $_config;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_config = array_merge(Module::getInstance()->thumbs, Module::getInstance()->defaultThumbs);
        
        if (! ($this->mediaFile instanceof ImageHandler)) {
            throw new ErrorException('Error class initialization.');
        }
    }
    
    /**
     * Generates thumb file name
     * 
     * @param int $width
     * @param int $height
     * @return string
     */
    protected function generateFileName($width, $height) {
        return pathinfo($this->mediaFile->activeRecord->filename, PATHINFO_FILENAME)
            . '-' . $width . 'x' . $height . '.'
            . pathinfo($this->mediaFile->activeRecord->filename, PATHINFO_EXTENSION);
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
    protected function createOne($alias)
    {
		if (false === ($sizes = $this->getAliasSizes($alias))) {
			return [];
		}
		
		list ($width, $height) = $sizes;
		
		Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];
		Image::thumbnail($this->mediaFile->absoluteFileName, $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
			implode('/', [
				$this->mediaFile->routes->getThumbsAbsolutePath(),
				$this->generateFileName($width, $height),
			])
		);
		
		$thumbnail = new Thumbnail([
			'alias' => $alias,
			'url' => implode('/', [
				$this->mediaFile->routes->getThumbsUrlPath(),
				$this->generateFileName($width, $height),
			]),
			'mediafile_id' => $this->mediaFile->activeRecord->id,
		]);
		
		return $thumbnail->save();
	}

    /**
     * Create thumbs for this image
     *
     * @return bool
     */
    public function create()
    {
        FileHelper::createDirectory($this->mediaFile->routes->getThumbsAbsolutePath(), 0777, true);
        
        $thumbs = [];
        
        foreach ($this->_config as $alias => $preset) {
            $this->createOne($alias);
        }
    }
    
    /**
     * @param $baseUrl
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
        $thumbs = $this->mediaFile->getThumbs();

        return !empty($thumbs[$alias]) ? $thumbs[$alias] : false;
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
			$this->mediaFile->routes->basePath,
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
        
        foreach ($this->mediaFile->getThumbs() as $alias => $url) {
            $list[] = [
				'alias' => $alias,
				'label' => $this->_config[$alias]['name'] . ' ' . $this->getSizes($this->getPath($alias)),
				'url' => $url,
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
        foreach ($this->mediaFile->getThumbs() as $thumbUrl) {
            unlink("{$this->mediaFile->routes->basePath}/{$thumbUrl}");
        }
        
        return true;
    }
}
