<?php

namespace vommuan\filemanager\models;

use vommuan\filemanager\Module;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * 
 */
class MediaFileSearch extends MediaFile
{
    /**
     * Number of files on one page
     */
    const PAGE_SIZE = 18;
    
    /**
     * Filter files by owner if it was configured
     * 
     * @param $query Link on Query object
     */
    protected function ownerFilter(&$query) {
		if (Module::getInstance()->manageOwnFiles || (Module::getInstance()->rbac && !Yii::$app->user->can('filemanagerManageFiles') && Yii::$app->user->can('filemanagerManageOwnFiles'))) {
			$query->joinWith('owner');
			if (Yii::$app->user->isGuest) {
				$query->andFilterWhere([Owner::tableName() . '.user_id' => 0]);
			} else {
				$query->andFilterWhere([Owner::tableName() . '.user_id' => Yii::$app->user->id]);
			}
		}
	}
    
    /**
     * Get last file on page
     * 
     * @param integer $page
     * @return MediaFileSearch
     */
    public function searchLastOnPage($page) {
		$query = self::find()->orderBy(['created_at' => SORT_DESC]);
		
		$this->ownerFilter($query);
		
		return $query->offset($page * self::PAGE_SIZE - 1)->one();
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
			'pagination' => [
				'defaultPageSize' => self::PAGE_SIZE,
				'route' => '/' . Module::getInstance()->uniqueId . '/file/page',
			],
		]);
		
		$this->ownerFilter($query);
		
		return $dataProvider;
    }
}
