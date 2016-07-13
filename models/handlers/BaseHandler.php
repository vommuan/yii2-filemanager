<?php
namespace vommuan\filemanager\models\handlers;

use Yii;
use yii\base\Model;
use yii\helpers\Inflector;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Routes;
use vommuan\filemanager\models\helpers\FileHelper;

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
	 * 
	 */
	public function afterSave($insert)
	{
		
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
		$status = $this->deleteFile();
		FileHelper::removeDirectory(
			implode('/', [
				$this->_routes->basePath,
				pathinfo($this->activeRecord->url, PATHINFO_DIRNAME),
			]),
			['onlyEmpty' => true]
		);
		
		return $status;
	}
	
	/**
	 * Get one variant of this file
	 * 
	 * @param string $alias alias of file variant
	 * @return string path to file
	 */
	public function getVariant($alias)
	{
		return false;
	}
	
	/**
     * Formating list of images to array for Html::dropDownList()
     * 
     * @param array $data array to format
     * ```
     * [
	 *     0 => [
	 *         'alias' => 'alias_1',
	 *         'label' => 'label_1',
	 *         'url' => 'url_1',
	 *     ],
	 *     1 => [
	 *         'alias' => 'alias_2',
	 *         'label' => 'label_2',
	 *         'url' => 'url_2',
	 *     ],
	 * ]
	 * ```
	 * @return array
	 * ```
	 * [
	 *     'url_1' => 'label_1',
	 *     'url_2' => 'label_2',
	 * ]
     */
    public function dropDownFormatter($input)
    {
		$output = [];
		
		for ($i = 0; $i < count($input); $i++) {
			$output[$input[$i]['url']] = $input[$i]['label'];
		}
		
		return $output;
	}
}