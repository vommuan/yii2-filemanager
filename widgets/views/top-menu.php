<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
	'renderInnerContainer' => false,
]);

echo Nav::widget([
	'options' => [
		'class' => 'navbar-nav navbar-left',
		'role' => 'navigation',
	],
	'items' => $menuItems,
]);

NavBar::end();
?>
