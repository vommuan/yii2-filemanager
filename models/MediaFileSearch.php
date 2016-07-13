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
use vommuan\filemanager\Module;
use vommuan\filemanager\models\Owners;
use Imagine\Image\ImageInterface;

/**
 *
 */
class MediaFileSearch extends MediaFile
{
    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
		return true;
	}
    
    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		$dataProvider = new ActiveDataProvider([
			'query' => self::find()->orderBy('created_at DESC'),
		]);

		$this->load($params);
		$dataProvider->pagination->defaultPageSize = 30;

		if (!$this->validate()) {
			return $dataProvider;
		}

		return $dataProvider;
    }
}
