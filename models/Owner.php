<?php

namespace vommuan\filemanager\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use vommuan\filemanager\Module;

/**
 * This is the model class for table "filemanager_mediafiles".
 *
 * @property integer $mediafile_id
 * @property integer $owner_id
 * @property string $owner
 * @property string $owner_attribute
 *
 * @property MediaFile $mediafile
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
     * @return \yii\db\ActiveQuery
     */
    public function getMediaFile()
    {
        return $this->hasOne(MediaFile::className(), ['id' => 'mediafile_id']);
    }
}
