<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
use yii\base\ErrorException;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use vommuan\filemanager\Module;

class MediaFile extends Model
{
	/**
	 * @var yii\web\UploadedFile
	 */
	public $file;
	
	/**
	 * @var MediaFileAR active record of media file
	 */
	public $activeRecord;
	
	/**
	 * @var Routes
	 */
	protected $_routes;
	
	/**
	 * @var MediaFileAR active record of media file
	 */
	protected $_ar;
	
	/**
	 * 
	 */
	protected function initRoutes()
	{
		$this->_routes = new Routes();
	}
	
	/**
	 * 
	 */
	protected function initActiveRecord()
	{
		if (isset($this->activeRecord)) {
			$this->_ar = $this->activeRecord;
		} else {
			$this->_ar = new MediaFileAR();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$this->initRoutes();
		$this->initActiveRecord();
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
	public function getId()
	{
		return $this->_ar->id;
	}
	
	/**
	 * Get url from active record
	 * 
	 * @return string
	 */
	public function getUrl()
	{
		return $this->_ar->url;
	}
	
	/**
	 * 
	 */
	public function getIcon($baseUrl)
	{
		return $baseUrl . '/images/file.png';
	}
	
	/**
	 * 
	 */
	public function getFileName()
	{
		return $this->_ar->filename;
	}
	
	/**
	 * 
	 */
	public function getDescription()
	{
		return $this->_ar->description;
	}
	
	/**
	 * 
	 */
	public function getType()
	{
		return $this->_ar->type;
	}
	
	/**
	 * 
	 */
	public function getSize()
	{
		return $this->_ar->size;
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
		
		return ($this->_ar->findByUrl($url)) ? true : false; // checks for existing url in db
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
			$filename = Inflector::slug($this->file->baseName) . $counter++ . '.' . $this->file->extension;
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
		$filename = Inflector::slug($this->file->baseName) . '.' . $this->file->extension;
		
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
			$this->_ar->filename,
		]);
	}
	
	/**
	 * 
	 */
	public function getAbsoluteFileName()
	{
		return implode('/', [
			$this->_routes->basePath,
			$this->_ar->url,
		]);
	}
	
	/**
	 * Save file in file system
	 * 
	 * @return boolean
	 */
	protected function fileSave()
	{
		if (false === ($this->_ar->filename = $this->generateFileName())) {
			return false;
		}
		
		$this->_ar->url = $this->generateUrl();
		
		FileHelper::createDirectory($this->_routes->absolutePath, 0777, true);
		
		$this->file->saveAs($this->absoluteFileName);
		
		$this->afterFileSave();
		
		return true;
	}
	
	/**
	 * 
	 */
	protected function afterFileSave() 
	{
		
	}
	
	/**
	 * Before save active record in database
	 */
	protected function beforeSave()
	{
		$this->_ar->type = $this->file->type;
		$this->_ar->size = $this->file->size;
	}
	
	/**
	 * Save file information in database
	 */
	public function dbSave()
	{
		$this->beforeSave();
		
		return $this->_ar->save();
	}
	
	/**
	 * Save just uploaded file
	 * 
	 * @return bool
	 */
	public function save()
	{
		if (false === $this->fileSave()) {
			return false;
		}
		
		return $this->dbSave();
	}
	
	/**
	 * Update information about file
	 */
	public function update($data)
	{
		if ($this->_ar->load($data, '') && $this->_ar->save()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Delete file
	 * 
	 * @param array $routes see routes in module config
	 * @return bool
	 */
	public function deleteFile()
	{
		return unlink("{$this->_routes->basePath}/{$this->url}");
	}

	/**
	 * @return int last changes timestamp
	 */
	public function getLastChanges()
	{
		return $this->_ar->getLastChanges();
	}
	
	/**
	 * @return string file size
	 */
	public function getFileSize()
	{
		Yii::$app->formatter->sizeFormatBase = 1000;
		
		return Yii::$app->formatter->asShortSize($this->_ar->size, 0);
	}
	
	/**
	 * 
	 */
	public static function find()
	{
		return MediaFileAR::find();
	}
}