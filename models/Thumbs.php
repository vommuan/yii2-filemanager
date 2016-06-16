<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use vommuan\filemanager\Module;
use yii\base\ErrorException;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\imagine\Image;
use Imagine\Image\ImageInterface;

/**
 * This is the helper model class for route paths
 */
class Thumbs extends Model
{
    public $mediaFile;
    private $_thumbsConfig;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_thumbsConfig = array_merge(Module::getInstance()->thumbs, Module::getInstance()->defaultThumbs);
        
        if (! ($this->mediaFile instanceof Mediafile)) {
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
    protected function generateThumbFileName($width, $height) {
        return pathinfo($this->mediaFile->url, PATHINFO_FILENAME)
            . '-' . $width . 'x' . $height . '.'
            . pathinfo($this->mediaFile->url, PATHINFO_EXTENSION);
    }

    /**
     * Create thumbs for this image
     *
     * @return bool
     */
    public function createThumbs()
    {
        FileHelper::createDirectory($this->mediaFile->routes->getThumbsAbsolutePath(), 0777, true);
        
        $thumbs = [];
        
        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];
        
        $originalFileName = implode('/', [
            $this->mediaFile->routes->absolutePath,
            pathinfo($this->mediaFile->url, PATHINFO_BASENAME),
        ]);

        foreach ($this->_thumbsConfig as $alias => $preset) {
            list ($width, $height) = $preset['size'];
            Image::thumbnail($originalFileName,    $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
                implode('/', [
                    $this->mediaFile->routes->getThumbsAbsolutePath(),
                    $this->generateThumbFileName($width, $height),
                ])
            );

            $thumbs[$alias] = implode('/', [
                $this->mediaFile->routes->getThumbsUrlPath(),
                $this->generateThumbFileName($width, $height),
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
    public function getDefaultThumbUrl($baseUrl = '')
    {
        if ($this->mediaFile->isImage()) {
            return $this->getThumbUrl('default');
        }
        
        return "{$baseUrl}/images/file.png";
    }
    
    /**
     * @return array thumbnails
     */
    public function getThumbs()
    {
        return unserialize($this->mediaFile->thumbs);
    }

    /**
     * @param string $alias thumb alias
     * @return string thumb url
     */
    public function getThumbUrl($alias)
    {
        $thumbs = $this->getThumbs();

        if ('original' === $alias) {
            return $this->mediaFile->url;
        }

        return ! empty($thumbs[$alias]) ? $thumbs[$alias] : '';
    }

    /**
     * Thumbnail image html tag
     *
     * @param string $alias thumbnail alias
     * @param array $options html options
     * @return string Html image tag
     */
    public function getThumbImage($alias, $options = [])
    {
        $url = $this->getThumbUrl($alias);

        if (empty($url)) {
            return '';
        }
        
        $options = array_merge(
            [
                // default options
                'alt' => $this->mediaFile->alt,
            ], 
            $options
        );
        
        return Html::img($url, $options);
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
            $preset = $this->_thumbsConfig[$alias];
            $list[$url] = $preset['name'] . ' ' . $preset['size'][0] . 'x' . $preset['size'][1];
        }
        
        return $list;
    }

    /**
     * Delete thumbnails for current image
     * 
     * @param array $routes see routes in module config
     */
    public function deleteThumbs()
    {
        foreach ($this->getThumbs() as $thumbUrl) {
            unlink("{$this->mediaFile->routes->basePath}/{$thumbUrl}");
        }
    }
}
