<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use vommuan\filemanager\Module;

/**
 * This is the model class for table "filemanager_owner".
 *
 * @property integer $mediafile_id
 * @property integer $user_id
 *
 * @property MediaFile $mediaFile
 */
class Owner extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filemanager_owner}}';
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
            [['mediafile_id', 'user_id'], 'required'],
            [['mediafile_id', 'user_id'], 'integer'],
            [['mediafile_id'], 'exist', 'skipOnError' => true, 'targetClass' => MediaFile::className(), 'targetAttribute' => ['mediafile_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Module::getInstance()->userClass, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * Get media file
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getMediaFile()
    {
        return $this->hasOne(MediaFile::className(), ['id' => 'mediafile_id']);
    }
    
    /**
     * Get user (owner)
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
		return $this->hasOne(Module::getInstance()->userClass, ['id' => 'user_id']);
	}
}
