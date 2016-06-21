<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use vommuan\filemanager\Module;
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
        
        if (! ($this->mediaFile instanceof MediaFile)) {
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
        return pathinfo($this->mediaFile->url, PATHINFO_FILENAME)
            . '-' . $width . 'x' . $height . '.'
            . pathinfo($this->mediaFile->url, PATHINFO_EXTENSION);
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
        
        $originalFileName = implode('/', [
            $this->mediaFile->routes->absolutePath,
            pathinfo($this->mediaFile->url, PATHINFO_BASENAME),
        ]);

        foreach ($this->_config as $alias => $preset) {
            list ($width, $height) = $preset['size'];
            Image::thumbnail($originalFileName, $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
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

        $this->mediaFile->thumbs = serialize($thumbs);
        $this->mediaFile->detachBehavior('timestamp');

        return $this->mediaFile->save();
    }
    
    /**
     * @param $baseUrl
     * @return string default thumbnail for image
     */
    public function getDefaultUrl($baseUrl = '')
    {
        if ($this->mediaFile->isImage()) {
            return $this->getUrl('default');
        }
        
        return "{$baseUrl}/images/file.png";
    }
    
    /**
     * @return array thumbnails
     */
    protected function getThumbs()
    {
        return unserialize($this->mediaFile->thumbs);
    }

    /**
     * @param string $alias thumb alias
     * @return string thumb url
     */
    public function getUrl($alias)
    {
        $thumbs = $this->getThumbs();

        if ('original' === $alias) {
            return $this->mediaFile->url;
        }

        return ! empty($thumbs[$alias]) ? $thumbs[$alias] : '';
    }

    /**
     * @param Module $module
     * @return array images list
     */
    public function getImagesList()
    {
        $thumbs = $this->getThumbs();
        $list = [];
        $list[$this->mediaFile->url] = Module::t('main', 'Original') . ' ' . $this->mediaFile->getOriginalImageSize();

        foreach ($thumbs as $alias => $url) {
            $preset = $this->_config[$alias];
            $list[$url] = $preset['name'] . ' ' . $preset['size'][0] . 'x' . $preset['size'][1];
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
        foreach ($this->getThumbs() as $thumbUrl) {
            unlink("{$this->mediaFile->routes->basePath}/{$thumbUrl}");
        }
    }
}
