<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\TopMenu;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset;?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container-fluid">
        <div class='row'>
			<div class='col-md-12'>
				<?php
				if (Module::getInstance()->rbac && (Yii::$app->user->can('filemanagerManageFiles') || Yii::$app->user->can('filemanagerManageOwnFiles') || Yii::$app->user->can('filemanagerManageSettings'))) {
					echo TopMenu::widget([
						'controller' => 'modal',
					]);
				}
				?>
				<?= $content;?>	
			</div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>