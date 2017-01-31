<?php

namespace vommuan\filemanager\widgets;

use yii\base\Widget;

/**
 * Page header widget
 * 
 * Usage:
 * 
 * ```php
 * <?= PageHeader::widget([
 *     'icon' => 'picture',
 *     'title' => 'Header',
 * ]);?>
 * ```
 */
class PageHeader extends Widget
{
	/**
	 * @var string glyphicon class
	 */
	public $icon;
	
	/**
	 * @var string
	 */
	public $title;
	
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('page-header', [
			'icon' => $this->icon,
			'title' => $this->title,
		]);
	}
}