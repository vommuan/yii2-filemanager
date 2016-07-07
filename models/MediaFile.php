<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use vommuan\filemanager\Module;
use vommuan\filemanager\models\handlers\HandlerFactory;

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
	/**
	 * @var
	 */
	protected $handler;
	
	/**
	 * 
	 */
	public $file;
	
	/**
	 * 
	 */
	protected function initHandler()
	{
		if (isset($this->file)) {
			$this->type = $this->file->type;
		}
		
		$this->handler = HandlerFactory::getHandler($this);
	}
	
	/**
	 * 
	 */
	public function init()
	{
		$this->initHandler();
	}
	
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
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'type', 'url', 'size'], 'required'],
            [['filename', 'type'], 'string', 'max' => 255],
            [['url', 'alt', 'description', 'thumbs'], 'string'],
            [['size'], 'integer'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getOwners()
    {
        return $this->hasMany(Owners::className(), ['mediafile_id' => 'id']);
    }
    
    /**
	 * 
	 */
	public function getIcon($baseUrl)
	{
		return $this->handler->getIcon($baseUrl);
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
     * @inheritdoc
     */
    public function beforeValidate()
    {
		if (isset($this->file)) {
			return $this->handler->beforeValidate();
		} else {
			return true;
		}
	}
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
		if (parent::beforeSave($insert)) {
			return $this->handler->beforeSave($insert);
		} else {
			return false;
		}
	}

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->handler->delete();
            
            foreach ($this->owners as $owner) {
                $owner->delete();
            }
            
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     */
	public function afterDelete()
	{
		parent::afterDelete();
	}
	
	/**
     * @inheritdoc
     */
    public function afterFind()
    {
		$this->initHandler();
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
     * @return int last changes timestamp
     */
    public function getLastChanges()
    {
        return !empty($this->updated_at) ? $this->updated_at : $this->created_at;
    }
}