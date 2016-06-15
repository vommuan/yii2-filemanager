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

	/**
	 * @inheritdoc
	 */
	public function init()
	{
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

        foreach ($this->mediaFile->thumbsConfig as $alias => $preset) {
            list ($width, $height) = $preset['size'];
            $mode = isset($preset['mode']) ? $preset['mode'] : ImageInterface::THUMBNAIL_OUTBOUND;
			
            Image::thumbnail($originalFileName,	$width, $height, $mode)->save(
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

        // create default thumbnail
        $this->createDefaultThumb();

        return $this->mediaFile->save();
    }

    /**
     * Create default thumbnail
     *
     * @param array $routes see routes in module config
     */
    public function createDefaultThumb()
    {
        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];

		$originalFileName = implode('/', [
			$this->mediaFile->routes->absolutePath,
			pathinfo($this->mediaFile->url, PATHINFO_BASENAME),
		]);
		
        list ($width, $height) = Module::getDefaultThumbSize();
        
        Image::thumbnail($originalFileName, $width, $height, ImageInterface::THUMBNAIL_OUTBOUND)->save(
			implode('/', [
				$this->mediaFile->routes->getThumbsAbsolutePath(),
				$this->generateThumbFileName($width, $height),
			])
		);
    }
}
