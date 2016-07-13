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
 * @property integer $created_at
 * @property integer $updated_at
 * @property Owners[] $owners
 * @property Thumbnail[] $thumbnails
 */
class MediaFile extends ActiveRecord
{
	/**
	 * @var vommuan\filemanager\models\handlers\BaseHandler or child class
	 */
	protected $handler;
	
	/**
	 * @var yii\web\UploadedFile uploaded file
	 */
	public $file;
	
	/**
	 * Initialization handler of file
	 */
	protected function initHandler()
	{
		if (isset($this->file)) {
			$this->type = $this->file->type;
		}
		
		$this->handler = HandlerFactory::getHandler($this);
	}
	
	/**
	 * @inheritdoc
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
            [['url', 'alt', 'description'], 'string'],
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
            'filename' => Module::t('main', 'Filename'),
            'type' => Module::t('main', 'Type'),
            'url' => Module::t('main', 'Url'),
            'alt' => Module::t('main', 'Alt attribute'),
            'size' => Module::t('main', 'Size'),
            'description' => Module::t('main', 'Description'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getThumbnails()
    {
		return $this->hasMany(Thumbnail::className(), ['mediafile_id' => 'id']);
	}
    
    /**
	 * Get icon url
	 * 
	 * @param string $baseUrl asset's base url
	 * @return string
	 */
	public function getIcon($baseUrl)
	{
		return $this->handler->getIcon($baseUrl);
	}
	
	/**
	 * Get file size
	 * 
	 * @return string size in KB
	 */
	public function getFileSize()
	{
		Yii::$app->formatter->sizeFormatBase = 1000;
		
		return Yii::$app->formatter->asShortSize($this->size, 0);
	}
	
	/**
	 * Get base file type from MIME-type (saved in database)
	 * 
	 * @return string
	 */
	public function getBaseType()
	{
		return substr($this->type, 0, strpos($this->type, '/'));
	}
	
	/**
	 * Get file width x height sizes
	 * 
	 * @param string $delimiter delimiter between width and height
     * @param string $format see [[ImageThumbnail::getSizes()]] for detailed documentation
     * @return string image size like '1366x768'
     */
	public function getSizes($delimiter = 'x', $format = '{w}{d}{h}')
	{
		if ('image' != $this->baseType) {
			return false;
		}
		
		return $this->handler->getSizes($delimiter, $format);
	}
	
	/**
	 * Get one variant of this file
	 * 
	 * @param string $alias alias of file variant
	 * @return string file url
	 */
	public function getFileVariant($alias = 'origin')
	{
		if ('origin' == $alias) {
			return $this->url;
		} else {
			return $this->handler->getVariant($alias);
		}
	}
	
	/**
	 * Get list variants of one file. For example, image variants are thumbs files.
	 * 
	 * @return array paths to files
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
	 * or formated array for using in drop down list
	 * ```
	 * [
	 *     'url_1' => 'label_1',
	 *     'url_2' => 'label_2',
	 * ]
	 * ```
	 */
	public function getFileVariants($dropDown = false)
	{
		if ('image' != $this->baseType) {
			$variants = [
				'alias' => 'origin',
				'label' => Module::t('main', 'Original'),
				'url' => $this->getFileVariant(),
			];
		} else {
			$variants = $this->handler->getVariantsList();
		}
		
		if ($dropDown) {
			return $this->handler->dropDownFormatter($variants);
		} else {
			return $variants;
		}
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
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		$this->handler->afterSave($insert);
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
     * @return int last changes timestamp
     */
    public function getLastChanges()
    {
        return !empty($this->updated_at) ? $this->updated_at : $this->created_at;
    }
}