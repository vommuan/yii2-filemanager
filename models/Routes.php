<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use vommuan\filemanager\Module;
use yii\base\ErrorException;

/**
 * This is the helper model class for route paths
 */
class Routes extends Model
{
    private $_routes;
    private $_absolutePath;
    private $_structure;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
		$this->_routes = array_merge(Module::getInstance()->defaultRoutes, Module::getInstance()->routes);
		$this->trimPaths();
	}
	
	public function getRoutes()
	{
		return $this->_routes;
	}
	
	public function getBasePath()
	{
		return $this->renderBasePath();
	}
    
    /**
	 * Remove start and end forward slashes
	 * 
	 * @param array $routes
	 * @return string
	 */
	protected function trimPaths()
	{
		foreach ($this->_routes as $key => $path) {
			$this->_routes[$key] = trim($path, '/');
		}
	}
	
	/**
	 * Compute url structure for upload file and save it in model
	 * 
	 * @return string
	 */
	public function getStructure()
	{
		if (isset($this->_structure)) {
			return $this->_structure;
		}
		
        $this->_structure = implode('/', [
			$this->renderUploadPath(),
			$this->renderDateDirFormat(),
		]);
		
		return $this->_structure;
	}
	
	/**
	 * Compute absolute path for upload file and save it in model
	 * 
	 * @return string
	 */
	public function getAbsolutePath()
	{
		if (isset($this->_absolutePath)) {
			return $this->_absolutePath;
		}
		
        $this->_absolutePath = implode('/', [
			$this->renderBasePath(),
			$this->structure,
		]);
		
		return $this->_absolutePath;
	}
	
	/**
	 * Render absolute base path
	 * 
	 * @return string
	 */
	protected function renderBasePath()
	{
		return Yii::getAlias($this->_routes['basePath']);
	}
	
	/**
	 * Render upload path
	 * 
	 * @return string
	 */
	protected function renderUploadPath()
	{
		return $this->_routes['uploadPath'];
	}
	
	/**
	 * Render upload date directory path
	 * 
	 * @return string
	 */
	protected function renderDateDirFormat()
	{
		return date($this->_routes['dateDirFormat'], time());
	}
	
	/**
	 * Render url thumbs path (directory without filename)
	 * 
	 * @return string
	 */
	protected function renderThumbsUrlPath($dateDir = null)
	{
		return str_replace(
			[
				'{uploadPath}',
				'{dateDirFormat}',
			], [
				$this->renderUploadPath(),
				(isset($dateDir) ? $dateDir : $this->renderDateDirFormat()),
			], 
			$this->_routes['thumbsDirTemplate']
		);
	}
	
	/**
	 * Render absolute thumbs path (directory without filename)
	 * 
	 * @return string
	 */
	protected function renderThumbsAbsolutePath($dateDir = null)
	{
		return implode('/', [
			$this->renderBasePath(),
			$this->renderThumbsUrlPath($dateDir),
		]);
	}
	
	/**
	 * Get date part of path from original database filename URL
	 * 
	 * @param string $originFileUrl
	 * @return string
	 */
	protected function getOriginDateDir($originFileUrl)
	{
		// get custom's directories from Module settings routes['thumbsDirTemplate']
		$customDir = array_map(
			function ($value) {
				return trim($value, '/');
			},
			array_filter(
				preg_split('/\{.*?\}/', $this->_routes['thumbsDirTemplate']),
				function($value) {
					if ('' === trim($value, '/')) {
						return false;
					} else {
						return true;
					}
				}
			)
		);
		
		array_unshift($customDir, $this->renderUploadPath());
		
		return trim(
			str_replace(
				$customDir,
				'',
				pathinfo($originFileUrl, PATHINFO_DIRNAME)
			), 
			'/'
		);
		
	}
	
	/**
	 * Get url thumbs path.
	 * 
	 * If $originFileUrl is defined then get url thumbs path from origin 
	 * file (directory without filename)
	 * 
	 * @return string
	 */
	public function getThumbsUrlPath($originFileUrl = null)
	{
		if (! isset($originFileUrl) || $this->getOriginDateDir($originFileUrl) == $this->renderDateDirFormat()) {
			return $this->renderThumbsUrlPath();
		} else {
			return $this->renderThumbsUrlPath($this->getOriginDateDir($originFileUrl));
		}
	}
	
	/**
	 * Get absolute thumbs path.
	 * 
	 * If $originFileUrl is defined then get absolute thumbs path from origin 
	 * file (directory without filename)
	 * 
	 * @return string
	 */
	public function getThumbsAbsolutePath($originFileUrl = null)
	{
		if (! isset($originFileUrl) || $this->getOriginDateDir($originFileUrl) == $this->renderDateDirFormat()) {
			return $this->renderThumbsAbsolutePath();
		} else {
			return $this->renderThumbsAbsolutePath($this->getOriginDateDir($originFileUrl));
		}
	}
}
