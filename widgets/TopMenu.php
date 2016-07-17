<?php
namespace vommuan\filemanager\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\Module;

class TopMenu extends Widget
{
	public $controller = 'file';
	
	/**
	 * @return array menu items
	 */
	protected function getManageFilesItems()
	{
		return [
			[
				'label' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-picture']) . ' ' . Module::t('main', 'Files'),
				'url' => Url::to([$this->controller . '/index']),
				'encode' => false,
			], [
				'label' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-upload']) . ' ' . Module::t('main', 'Upload'),
				'url' => Url::to([$this->controller . '/upload-manager']),
				'encode' => false,
			],
		];
	}
	
	/**
	 * @return array menu items
	 */
	protected function getManageSettingsItems()
	{
		return [
			[
				'label' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-wrench']) . ' ' . Module::t('main', 'Settings'),
				'url' => Url::to(['setting/index']),
				'encode' => false,
			],
		];
	}
	
	public function run()
	{
		$menuItems = [];
		
		if (Module::getInstance()->rbac && Yii::$app->user->can('filemanagerManageOwnFiles')) {
			$menuItems = array_merge($menuItems, $this->getManageFilesItems());
		}
		
		if ('modal' != $this->controller && Module::getInstance()->rbac && Yii::$app->user->can('filemanagerManageSettings')) {
			$menuItems = array_merge($menuItems, $this->getManageSettingsItems());
		}
		
		return $this->render('top-menu', [
			'menuItems' => $menuItems,
		]);
	}
}