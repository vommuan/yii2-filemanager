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
     * Number of files on one page
     */
    const PAGE_SIZE = 30;
    
    /**
     * Get last file on page
     * 
     * @param integer $page
     * @return MediaFileSearch
     */
    public function searchLastOnPage($page) {
		return self::find()->orderBy(['created_at' => SORT_DESC])
			->offset($page * self::PAGE_SIZE - 1)->one();
	}
    
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

		$dataProvider->pagination->defaultPageSize = self::PAGE_SIZE;
		
		if (Module::getInstance()->manageOwnFiles || (Module::getInstance()->rbac && !Yii::$app->user->can('filemanagerManageFiles') && Yii::$app->user->can('filemanagerManageOwnFiles'))) {
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
