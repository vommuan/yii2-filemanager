<?php
namespace vommuan\filemanager\models;

use yii\data\ActiveDataProvider as YiiActiveDataProvider;

class ActiveDataProvider extends YiiActiveDataProvider
{
	/**
	 * 
	 */
	protected function wrapModels($models)
	{
		$output = [];
		foreach ($models as $model) {
			$output[] = MediaFileFactory::getOne($model->id);
		}
		
		return $output;
	}
	
	/**
	 * @inheritdoc
	 */
	public function getModels()
	{
		return $this->wrapModels(parent::getModels());
	}
}