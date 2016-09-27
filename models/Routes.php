<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use yii\base\UserException;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\helpers\FileHelper;

/**
 * This is the helper model class for route paths
 */
class Routes extends Model
{
    private $_config;
    private $_dateDir;
    
    /**
     * Remove start and end forward slashes
     * 
     * @param array $routes
     * @return string
     */
    private function trimPaths()
    {
        foreach ($this->_config as $key => $path) {
			if (is_string($this->_config[$key])) {
				$this->_config[$key] = trim($path, '/');
			}
        }
    }
    
    /**
     * Create upload directory if it possible
     */
    private function initUploadPath()
    {
		try {
			FileHelper::createDirectory($this->uploadPath, 0777, true);
		} catch (\yii\base\Exception $e) {
			throw new UserException($e->getMessage(), $e->getCode());
		}
	}
    
    /**
     * Create symblic link for @webroot path
     * If Module::$routes['uploadPath'] contains @webroot, symbolic link will not created
     */
    private function initSymLink()
    {
		if (false === strpos($this->uploadPath, Yii::getAlias('@app/web'))) {
			$link = Yii::getAlias('@app/web') . '/' . $this->_config['symLink'];
			
			if (!is_link($link)) {
				try {
					symlink($this->uploadPath, $link);
				} catch (\Exception $e) {
					throw new UserException("Failed to create symbolic link \"{$link}\": " . $e->getMessage(), $e->getCode(), $e);
				}
			}
		}
	}
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_config = array_merge(Module::getInstance()->defaultRoutes, Module::getInstance()->routes);
        $this->trimPaths();
        $this->initUploadPath();
        $this->initSymLink();
    }
    
    /**
     * Get base path of web directory
     * 
     * @return string
     */
    protected function getUploadPath()
    {
        return Yii::getAlias($this->_config['uploadPath']);
    }
    
    /**
     * 
     * @return string
     */
    protected function getBaseUrlPath()
    {
		if (0 === strpos($this->uploadPath, Yii::getAlias('@app/web'))) {
			return ltrim(
				str_replace(Yii::getAlias('@app/web'), '', $this->uploadPath), 
				'/'
			);
		} else {
			return $this->_config['symLink'];
		}
	}
    
    /**
     * Get file or directory path by it url
     * 
     * @param string $url
     * @return string
     */
    public function getPathByUrl($url)
    {
		return $this->uploadPath . str_replace($this->baseUrlPath, '', $url);
	}
	
	/**
     * Get upload date directory path and save it in model
     * 
     * @return string
     */
    protected function getDateDirectory()
    {
        if (isset($this->_dateDir)) {
			return $this->_dateDir;
		}
        
        $this->_dateDir = date($this->_config['dateDirFormat'], time());
        
        return $this->_dateDir;
    }
    
    /**
	 * 
	 */
	protected function setDateDirectory($dateDir)
	{
		$this->_dateDir = $dateDir;
	}
    
    /**
     * Compute url path for upload file
     * 
     * @return string
     */
    public function getUrlPath()
    {
        return $this->baseUrlPath . '/' . $this->dateDirectory;
    }
    
    /**
     * Compute absolute path for upload file
     * 
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->uploadPath . '/' . $this->dateDirectory;
    }
    
    /**
     * Get date part of path from original database filename URL
     * 
     * @param string $fileUrl original file url
     * @return string
     */
    protected function getOriginDateDirectory($fileUrl)
    {
        return trim(str_replace($this->baseUrlPath, '', pathinfo($fileUrl, PATHINFO_DIRNAME)), '/');
    }
    
    /**
     * 
     * @return string
     */
    protected function renderThumbnailDirectory()
    {
		return str_replace('{dateDirFormat}', $this->dateDirectory, $this->_config['thumbsDirTemplate']);
	}
    
    /**
     * Get url thumbs path.
     * 
     * @param string $fileUrl origin file url
     * @return string
     */
    public function getThumbsUrlPath($fileUrl)
    {
        $this->dateDirectory = $this->getOriginDateDirectory($fileUrl);
        
        return $this->baseUrlPath . '/' . $this->renderThumbnailDirectory();
    }
    
    /**
     * Get absolute thumbs path.
     * 
     * @param string $fileUrl origin file url
     * @return string
     */
    public function getThumbsAbsolutePath($fileUrl)
    {
        $this->dateDirectory = $this->getOriginDateDirectory($fileUrl);
        
        return $this->uploadPath . '/' . $this->renderThumbnailDirectory();
    }
}
