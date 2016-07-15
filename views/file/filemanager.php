<?php

use vommuan\filemanager\assets\FilemanagerAsset;
use vommuan\filemanager\Module;
use yii\helpers\ArrayHelper;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['moduleBundle'] = FilemanagerAsset::register($this);
?>

<div class="container-fluid">
	<div class="page-header">
		<h1>
			<span class="glyphicon glyphicon-picture"></span>
			<?= Module::t('main', 'File manager');?>
		</h1>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
			<?= Html::a(
				Html::tag('span', '', ['class' => 'glyphicon glyphicon-upload']) . ' ' . Module::t('main', 'Upload manager'),
				['file/uploadmanager'], [
					'class' => 'btn btn-primary',
				]
			); ?>
		</div>
	</div>
	<div class="row">
		<div id="filemanager" class="col-xs-12" data-url-info="<?= Url::to(['file/info']);?>">
			<?= ListView::widget([
				'dataProvider' => $dataProvider,
				'layout' => 
					Html::tag('div', '{summary}', ['class' => 'col-xs-12']) 
					. Html::tag('div', '{pager}', ['class' => 'col-xs-12'])
					. Html::tag('div', '{items}', ['class' => 'col-xs-12 items'])
					. Html::tag('div', '{pager}', ['class' => 'col-xs-12']),
				'options' => [
					'class' => 'files-gallery row',
				],
				'itemOptions' => [
					'class' => 'col-xs-4 col-sm-2 item',
				],
				'itemView' => function ($model, $key, $index, $widget) {
					return Html::a(
						Html::img($model->getIcon($this->params['moduleBundle']->baseUrl))
							. Html::tag('span', '', ['class' => 'glyphicon glyphicon-check checked']),
						'#mediafile', [
							'class' => 'thumbnail',
							'data-key' => $key,
						]
					);
				},
			]);?>
		</div>
	</div>
</div>
<div id="fileinfo"></div>