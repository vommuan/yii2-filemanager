<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\base\ErrorException;
use yii\imagine\Image;
use yii\data\ActiveDataProvider;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Owners;

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
 * @property Tag[] $tags
 */
class MediaFile extends ActiveRecord
{
    private $_routes;
    private $_thumbFiles;
    
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
     * @var array|null
     */
    protected $tagIds = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanager_mediafile}}';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_routes = new Routes();
        $this->_thumbFiles = new Thumbs([
            'mediaFile' => $this,
        ]);

        $linkTags = function ($event) {
            if ($this->tagIds === null) {
                return;
            }
            if (!is_array($this->tagIds)) {
                $this->tagIds = [];
            }
            $whereIds = $models = $newTagIds = [];
            foreach ($this->tagIds as $tagId) {
                if (empty($tagId)) {
                    continue;
                }
                if (preg_match("/^\d+$/", $tagId)) {
                    $whereIds[] = $tagId;
                    continue;
                }
                // если tagId не число, то значит надо создать новый тег
                if (!$tag = Tag::findOne(['name' => $tagId])) {
                    $tag = new Tag();
                    $tag->name = $tagId;
                    if (!$tag->save()) {
                        continue;
                    }
                }
                $newTagIds[] = $tag->id;
                $models[] = $tag;
            }

            $this->unlinkAll('tags', true);
            if ($whereIds) {
                $models = array_merge($models, Tag::find()->where(['id' => $whereIds])->all());
            }
            foreach ($models as $model) {
                $this->link('tags', $model);
            }
            // что бы после сохранения в значение были новые теги
            $this->tagIds = array_merge($whereIds, $newTagIds);
        };

        $this->on(static::EVENT_AFTER_INSERT, $linkTags);
        $this->on(static::EVENT_AFTER_UPDATE, $linkTags);
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
            [['file'], 'file'],
            [['tagIds'], 'safe'],
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
            'tagIds' => Module::t('main', 'Tags'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('filemanager_mediafile_tag', ['mediafile_id' => 'id']);
    }

    /**
     * @return array|null
     */
    public function getTagIds() {
        return $this->tagIds !== null ? $this->tagIds : array_map(function ($tag) {
            return $tag->id;
        }, $this->tags);
    }

    /**
     * @param $value
     */
    public function setTagIds($value) {
        $this->tagIds = $value;
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

	public function afterDelete()
	{
		parent::afterDelete();
		Tag::removeUnusedTags();
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
     * Crop image into max sizes with saving proportions
     * Array indexes: 0 - width, 1 - height
     * 
     * @return void
     */
    protected function cropImage()
    {
        $maxSizes = Module::getInstance()->maxImageSizes;
        $ignoreRotate = Module::getInstance()->ignoreImageRotate;
        
        if (! is_array($maxSizes) || count($maxSizes) != 2) {
			throw new ErrorException('Error module "vommuan\\filemanager\\' . Module::className() . '" settings: maxImageSizes');
		}
		
		$fileName = implode('/', [
			$this->_routes->basePath,
			$this->url,
		]);
		
		$originSizes = array_slice(getimagesize($fileName), 0, 2);
		
		if ($ignoreRotate) {
			$isVertical = ($originSizes[0] < $originSizes[1]) ? true : false;
			sort($originSizes, SORT_NUMERIC);
			sort($maxSizes, SORT_NUMERIC);
		}
		
        // if original image sizes less or equal than max image sizes in settings
        if ($originSizes[0] <= $maxSizes[0] && $originSizes[1] <= $maxSizes[1]) {
			return;
		}
		
		$newSizes = [];
        
        $originProportions = $originSizes[0] / $originSizes[1];
		$newSizes[0] = $maxSizes[0];
		$newSizes[1] = $newSizes[0] / $originProportions;
		
		if ($maxSizes[1] < $newSizes[1]) {
			$newSizes[1] = $maxSizes[1];
			$newSizes[0] = $newSizes[1] * $originProportions;
		}
		
		if ($ignoreRotate && ! $isVertical) {
			rsort($newSizes);
		}
		
		Image::thumbnail($fileName, round($newSizes[0]), round($newSizes[1]))->save($fileName);
		
		$this->size = filesize($fileName);
		$this->save();
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
			if (isset(Module::getInstance()->maxImageSizes)) {
				$this->cropImage();
			}
			
            $this->_thumbFiles->create();
        }
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
        return ! empty($this->updated_at) ? $this->updated_at : $this->created_at;
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
