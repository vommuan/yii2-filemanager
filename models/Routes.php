<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;

/**
 * This is the helper model class for route paths
 */
class Routes extends Model
{
    public $routes;
    private $_absolutePath;
    private $_structure;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
		if (! is_array($this->routes)) {
			throw new ErrorException('Routes must be an array.');
		}
		
		$this->trimPaths();
	}
    
    /**
	 * Remove start and end forward slashes
	 * @param array $routes
	 * @return string
	 */
	protected function trimPaths()
	{
		foreach ($this->routes as $key => $path) {
			$this->routes[$key] = trim($path, '/');
		}
	}
	
	/**
	 * Compute url structure for upload file and save it in model
	 * @return string
	 */
	public function getStructure()
	{
		if (isset($this->_structure)) {
			return $this->_structure;
		}
		
        $this->_structure = implode('/', [
			$this->routes['baseUrl'],
			$this->routes['uploadPath'],
			date($this->routes['dirFormat'], time()),
		]);
		
		return $this->_structure;
	}
	
	/**
	 * Compute absolute path for upload file and save it in model
	 * @return string
	 */
	public function getAbsolutePath()
	{
		if (isset($this->_absolutePath)) {
			return $this->_absolutePath;
		}
		
        $this->_absolutePath = implode('/', [
			Yii::getAlias($this->routes['basePath']),
			$this->getStructure($this->routes),
		]);
		
		return $this->_absolutePath;
	}
}
