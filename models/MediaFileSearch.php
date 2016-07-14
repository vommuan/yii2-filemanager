<?php
namespace vommuan\filemanager\models;

use Yii;
use yii\data\ActiveDataProvider;
use vommuan\filemanager\Module;

/**
 * 
 */
class MediaFileSearch extends MediaFile
{
    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search()
    {
		$query = self::find()->orderBy(['created_at' => SORT_DESC]);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$dataProvider->pagination->defaultPageSize = 30;
		
		if (Module::getInstance()->manageOwnFiles) {
			$query->joinWith('owner');
			if (Yii::$app->user->isGuest) {
				$query->andFilterWhere([Owner::tableName() . '.user_id' => 0]);
			} else {
				$query->andFilterWhere([Owner::tableName() . '.user_id' => Yii::$app->user->id]);
			}
		}

		return $dataProvider;
    }
}
