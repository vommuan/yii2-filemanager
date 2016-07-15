<?php
namespace vommuan\filemanager\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use vommuan\filemanager\Module;

class Thumbnail extends ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanager_thumbnail}}';
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
            [['alias', 'url', 'mediafile_id'], 'required'],
            [['alias'], 'string', 'max' => 255],
            [['url'], 'string'],
            [['mediafile_id'], 'integer'],
            [['mediafile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaFile::className(), 'targetAttribute' => ['mediafile_id' => 'id']],
        ];
	}
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('main', 'ID'),
            'alias' => Module::t('main', 'Alias'),
            'url' => Module::t('main', 'Url'),
            'mediafile_id' => Module::t('main', 'Media File'),
            'created_at' => Module::t('main', 'Created'),
            'updated_at' => Module::t('main', 'Updated'),
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
	public function getMediaFile()
	{
		return $this->hasOne(MediaFile::className(), ['id' => 'mediafile_id']);
	}
}