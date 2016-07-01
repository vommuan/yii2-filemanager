<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\base\Model;
//use yii\db\ActiveRecord;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Owners;

class MediaFile extends Model
{
    protected $_routes;
    protected $_thumbFiles;
    
    public $file;
    
    /**
     * @var MediaFileAR active record of media file
     */
    protected $_ar;

    public static $imageFileTypes = [
        'image/gif',
        'image/jpeg',
        //'image/pjpeg',
        'image/png',
        //'image/svg+xml',
        //'image/tiff',
        //'image/vnd.microsoft.icon',
        //'image/vnd.wap.wbmp',
    ];

    /**
     * @var array|null
     */
    protected $tagIds = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_ar = new MediaFileAR();
        
        $this->_routes = new Routes();
        $this->_thumbFiles = new Thumbs([
            'mediaFile' => $this,
        ]);
    }

    /**
     * @return array|null
     */
    public function getTagIds() {
        return ($this->tagIds !== null)
			? $this->tagIds 
			: array_map(
				function ($tag) {
					return $tag->id;
				},
				$this->tags
			);
    }

    /**
     * @param $value
     */
    public function setTagIds($value) {
        $this->tagIds = $value;
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
     * Get access for readonly Thumbs object
     * 
     * @return vommuan\filemanager\models\Thumbs
     */
    public function getThumbFiles()
    {
        return $this->_thumbFiles;
    }
    
    /**
     * Check if current file name is exists
     * 
     * @param string $filename
     * @return bool 
     */
    protected function fileNameExists($filename)
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
        } while ($this->fileNameExists($filename));
        
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
        if ($this->fileNameExists($filename)) {
            if (false === Module::getInstance()->rename) {
                return false;
            } else {
                $filename = $this->getUniqueFileName();
            }
        }
        
        return $filename;
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
        
        FileHelper::createDirectory($this->_routes->absolutePath, 0777, true);
        
        $this->file->saveAs(
            implode('/', [
                $this->_routes->absolutePath,
                $this->_ar->filename,
            ])
        );
        
        return true;
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
	 * Get serialized thumbs information from active record
	 * 
	 * @return string
	 */
	public function getThumbs()
	{
		return $this->_ar->thumbs;
	}
	
	/**
	 * Save file information in database
	 */
	public function dbSave()
	{
		$this->_ar->type = $this->file->type;
        $this->_ar->size = $this->file->size;
        $this->_ar->url = implode('/', [
            $this->_routes->structure,
            $this->_ar->filename,
        ]);
        
        return $this->_ar->save();
	}
	
    /**
     * Save just uploaded file
     * 
     * @return bool
     */
    public function saveUploadedFile()
    {
        if (false === $this->fileSave()) {
			return false;
		}
        
        return $this->dbSave();
    }
    
    /**
     * @return bool if type of this media file is image, return true;
     */
    public function isImage()
    {
        return in_array($this->type, self::$imageFileTypes);
    }
    
    /**
     * This method wrap getimagesize() function
     * 
     * @param array $routes see routes in module config
     * @param string $delimiter delimiter between width and height
     * @return string image size like '1366x768'
     */
    public function getOriginalImageSize($delimiter = 'x')
    {
        $imageSizes = getimagesize(
            implode('/', [
                $this->_routes->basePath,
                $this->url,
            ])
        );
        
        return implode($delimiter, [
            $imageSizes[0],
            $imageSizes[1],
        ]);
    }
    
    /**
     * Add owner to mediafiles table
     *
     * @param int $owner_id owner id
     * @param string $owner owner identification name
     * @param string $owner_attribute owner identification attribute
     * @return bool save result
     */
    public function addOwner($owner_id, $owner, $owner_attribute)
    {
        $mediafiles = new Owners();
        $mediafiles->mediafile_id = $this->id;
        $mediafiles->owner = $owner;
        $mediafiles->owner_id = $owner_id;
        $mediafiles->owner_attribute = $owner_attribute;

        return $mediafiles->save();
    }

    /**
     * Remove this mediafile owner
     *
     * @param int $owner_id owner id
     * @param string $owner owner identification name
     * @param string $owner_attribute owner identification attribute
     * @return bool delete result
     */
    public static function removeOwner($owner_id, $owner, $owner_attribute)
    {
        $mediafiles = Owners::findOne([
            'owner_id' => $owner_id,
            'owner' => $owner,
            'owner_attribute' => $owner_attribute,
        ]);

        if ($mediafiles) {
            return $mediafiles->delete();
        }

        return false;
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
        return Yii::$app->formatter->asShortSize($this->size, 0);
    }

    /**
     * Search models by file types
     * 
     * @param array $types file types
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByTypes(array $types)
    {
        return self::find()->filterWhere(['in', 'type', $types])->all();
    }

    public static function loadOneByOwner($owner, $owner_id, $owner_attribute)
    {
        $owner = Owners::findOne([
            'owner' => $owner,
            'owner_id' => $owner_id,
            'owner_attribute' => $owner_attribute,
        ]);

        if ($owner) {
            return $owner->mediafile;
        }

        return false;
    }
}
