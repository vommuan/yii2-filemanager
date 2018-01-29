<?php
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\TopMenu;

$this->beginContent(Module::getInstance()->parentLayout);?>

	<?php
	if (!Module::getInstance()->rbac || (Yii::$app->user->can('filemanagerManageFiles') || Yii::$app->user->can('filemanagerManageOwnFiles') || Yii::$app->user->can('filemanagerManageSettings'))) {
		echo TopMenu::widget();
	}
	?>
	<?= $content;?>
	
	<?php 
$this->endContent();
