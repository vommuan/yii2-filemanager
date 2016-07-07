<?php
namespace vommuan\filemanager\models\handlers;

use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Routes;

class BaseHandler extends Model
{
	/**
	 * @var Routes
	 */
	protected $_routes;
	
	/**
	 * @var MediaFile
	 */
	public $activeRecord;
	
	/**
	 * 
	 */
	protected function initRoutes()
	{
		$this->_routes = new Routes();
	}
	
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->initRoutes();
	}
	
	/**
	 * Get access for readonly Routes object
	 * 
	 * @return vommuan\filemanager\models\Routes
	 */
	public function getRoutes()
	{
		return $this->_routes;
	}
	
	/**
	 * 
	 */
	public function getIcon($baseUrl)
	{
		return $baseUrl . '/images/file.png';
	}
	
	/**
	 * Check if current file name is exists
	 * 
	 * @param string $filename
	 * @return bool 
	 */
	protected function fileExists($filename)
	{
		$url = implode('/', [
			$this->_routes->structure,
			$filename,
		]);
		
		return ($this->activeRecord->findByUrl($url)) ? true : false; // checks for existing url in db
	}
	
	/**
	 * Get unique file name with index. Used when current file name is exists
	 * 
	 * @return string
	 */
	protected function getUniqueFileName()
	{
		$counter = 0;
		
		do {
			$filename = Inflector::slug($this->activeRecord->file->baseName) . $counter++ 
				. '.' . $this->activeRecord->file->extension;
		} while ($this->fileExists($filename));
		
		return $filename;
	}
	
	/**
	 * Generate unique file name for uploaded file
	 * 
	 * @return mixed [string|boolean] if setting 'rename' set to 'false' 
	 * return false, when filename is exist
	 */
	protected function generateFileName()
	{
		$filename = Inflector::slug($this->activeRecord->file->baseName) . '.' . $this->activeRecord->file->extension;
		
		//if a file with the same name already exist append a number
		if ($this->fileExists($filename)) {
			if (false === Module::getInstance()->rename) {
				return false;
			} else {
				$filename = $this->getUniqueFileName();
			}
		}
		
		return $filename;
	}
	
	/**
	 * 
	 */
	protected function generateUrl()
	{
		return implode('/', [
			$this->_routes->structure,
			$this->activeRecord->filename,
		]);
	}
	
	/**
	 * 
	 */
	public function getAbsoluteFileName()
	{
		return implode('/', [
			$this->_routes->basePath,
			$this->activeRecord->url,
		]);
	}
	
	/**
	 * Save file in file system
	 * 
	 * @return boolean
	 */
	protected function fileSave()
	{
		FileHelper::createDirectory($this->_routes->absolutePath, 0777, true);
		
		Yii::info($this->absoluteFileName);
		
		$this->activeRecord->file->saveAs($this->absoluteFileName);
		
		$this->afterFileSave();
		
		return true;
	}
	
	/**
	 * 
	 */
	protected function afterFileSave() 
	{
		return true;
	}
	
	/**
	 * 
	 */
	public function beforeValidate()
	{
		if (false === ($this->activeRecord->filename = $this->generateFileName())) {
			return false;
		}
		
		$this->activeRecord->url = $this->generateUrl();
		$this->activeRecord->size = $this->activeRecord->file->size;
		
		return true;
	}
	
	/**
	 * 
	 */
	public function beforeSave($insert)
	{
		if ($insert) {
			return $this->fileSave();
		} else {
			return true;
		}
	}
	
	/**
	 * Delete file
	 * 
	 * @param array $routes see routes in module config
	 * @return bool
	 */
	protected function deleteFile()
	{
		return unlink("{$this->_routes->basePath}/{$this->activeRecord->url}");
	}
	
	/**
	 * 
	 */
	public function delete()
	{
		return $this->deleteFile();
	}
}