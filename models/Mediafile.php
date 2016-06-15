<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Owners;
use Imagine\Image\ImageInterface;

/**
 * This is the model class for table "{{%filemanager_mediafile}}".
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property string $url
 * @property string $alt
 * @property integer $size
 * @property string $description
 * @property string $thumbs
 * @property integer $created_at
 * @property integer $updated_at
 * @property Owners[] $owners
 */
class Mediafile extends ActiveRecord
{
    private $_routes;
    private $_absolutePath;
    private $_structure;
    
    public $thumbsConfig;
    public $rename;
    public $file;

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
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanager_mediafile}}';
    }
    
    public function init()
    {
		$this->_routes = new Routes();
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'type', 'url', 'size'], 'required'],
            [['url', 'alt', 'description', 'thumbs'], 'string'],
            [['created_at', 'updated_at', 'size'], 'integer'],
            [['filename', 'type'], 'string', 'max' => 255],
            [['file'], 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('main', 'ID'),
            'filename' => Module::t('main', 'filename'),
            'type' => Module::t('main', 'Type'),
            'url' => Module::t('main', 'Url'),
            'alt' => Module::t('main', 'Alt attribute'),
            'size' => Module::t('main', 'Size'),
            'description' => Module::t('main', 'Description'),
            'thumbs' => Module::t('main', 'Thumbnails'),
            'created_at' => Module::t('main', 'Created'),
            'updated_at' => Module::t('main', 'Updated'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwners()
    {
        return $this->hasMany(Owners::className(), ['mediafile_id' => 'id']);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            foreach ($this->owners as $owner) {
                $owner->delete();
            }
            
            return true;
        } else {
            return false;
        }
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
		
		return (self::findByUrl($url)) ? true : false; // checks for existing url in db
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
     * Save just uploaded file
     * 
     * @return bool
     */
    public function saveUploadedFile()
    {
        FileHelper::createDirectory($this->_routes->absolutePath, 0777, true);
        
        // get file instance
        $this->file = UploadedFile::getInstance($this, 'file');
        
        //if a file with the same name already exist append a number
        $filename = Inflector::slug($this->file->baseName) . '.' . $this->file->extension;
		if ($this->fileNameExists($filename)) {
			if (false === $this->rename) {
				return false;
			} else {
				$filename = $this->getUniqueFileName();
			}
		}
		
		// save original uploaded file
        $this->file->saveAs(
			implode('/', [
				$this->_routes->absolutePath,
				$filename,
			])
		);
        $this->filename = $filename;
        $this->type = $this->file->type;
        $this->size = $this->file->size;
        $this->url = implode('/', [
			$this->_routes->structure,
			$filename,
		]);
		
        $this->save();
        
        if ($this->isImage()) {
            $thumbs = new Thumbs([
				'mediaFile' => $this,
			]);
            $thumbs->createThumbs();
        }
    }
    
    /**
     * Create thumbs for this image
     *
     * @return bool
     */
    public function createThumbs()
    {
		$thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		
		return $thumbs->createThumbs();
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
     * @return bool if type of this media file is image, return true;
     */
    public function isImage()
    {
        return in_array($this->type, self::$imageFileTypes);
    }

    /**
     * @param $baseUrl
     * @return string default thumbnail for image
     */
    public function getDefaultThumbUrl($baseUrl = '')
    {
		$thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		
		return $thumbs->getDefaultThumbUrl($baseUrl);
    }
    
    /**
     * @param string $alias thumb alias
     * @return string thumb url
     */
    public function getThumbUrl($alias)
    {
        $thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		
		return $thumbs->getThumbUrl($alias);
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
        $thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		
		return $thumbs->getThumbImage($alias, $options);
    }

    /**
     * @param Module $module
     * @return array images list
     */
    public function getImagesList()
    {
		$thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		
		return $thumbs->getImagesList();
    }

    /**
     * Delete thumbnails for current image
     * 
     * @param array $routes see routes in module config
     */
    public function deleteThumbs(array $routes)
    {
        $thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		$thumbs->deleteThumbs();
    }

    /**
     * Delete file
     * 
     * @param array $routes see routes in module config
     * @return bool
     */
    public function deleteFile(array $routes)
    {
        $basePath = Yii::getAlias($routes['basePath']);
        return unlink("{$basePath}/{$this->url}");
    }

    /**
     * Creates data provider instance with search query applied
     * 
     * @return ActiveDataProvider
     */
    public function search()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => self::find()->orderBy('created_at DESC'),
        ]);
        
        $dataProvider->pagination->defaultPageSize = 15;
        
        return $dataProvider;
    }

    /**
     * @return int last changes timestamp
     */
    public function getLastChanges()
    {
        return ! empty($this->updated_at) ? $this->updated_at : $this->created_at;
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
        $thumbs = new Thumbs([
			'mediaFile' => $this,
		]);
		return $thumbs->getOriginalImageSize($delimiter);
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
     * Find model by url
     *
     * @param $url
     * @return static
     */
    public static function findByUrl($url)
    {
        return self::findOne(['url' => $url]);
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
