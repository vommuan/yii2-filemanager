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
     * Create thumbs for this image
     *
     * @return bool
     */
    public function create()
    {
        FileHelper::createDirectory($this->mediaFile->routes->getThumbsAbsolutePath(), 0777, true);
        
        $thumbs = [];
        
        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];
        
        foreach ($this->_config as $alias => $preset) {
            list ($width, $height) = $preset['size'];
            Image::thumbnail($this->mediaFile->absoluteFileName, $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
                implode('/', [
                    $this->mediaFile->routes->getThumbsAbsolutePath(),
                    $this->generateFileName($width, $height),
                ])
            );

            $thumbs[$alias] = implode('/', [
                $this->mediaFile->routes->getThumbsUrlPath(),
                $this->generateFileName($width, $height),
            ]);
        }

        $this->mediaFile->setThumbs($thumbs);
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

        return !empty($thumbs[$alias]) ? $thumbs[$alias] : '';
    }

    /**
     * @param Module $module
     * @return array images list
     */
    public function getThumbsList()
    {
        $list = [];
        
        foreach ($this->mediaFile->getThumbs() as $alias => $url) {
            $list[$url] = $this->_config[$alias]['name'] . ' ' 
				. $this->_config[$alias]['size'][0] . ' x ' . $this->_config[$alias]['size'][1];
        }
        
        return $list;
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
