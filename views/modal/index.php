<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\fileupload\FileUploadUI;
use vommuan\filemanager\Module;
use vommuan\filemanager\assets\ModalAsset;
use vommuan\filemanager\assets\FilemanagerAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('main', 'Files');

ModalAsset::register($this);
$this->params['moduleBundle'] = FilemanagerAsset::register($this);
?>

<div class="page-header">
	<h1>
		<span class="glyphicon glyphicon-picture"></span>
		<?= $this->title;?>
	</h1>
</div>

<div class="row">
	<div class="col-xs-12">
		<?= FileUploadUI::widget([
			'model' => $uploadModel,
			'attribute' => 'file',
			'clientOptions' => [
				'autoUpload' => Module::getInstance()->autoUpload,
			],
			'url' => ['upload'],
			'uploadTemplateView' => '/fileuploadui/upload',
			'formView' => '/fileuploadui/form',
			'gallery' => false,
		]);?>
	</div>
</div>

<div class="row">
	<div id="gallery" class="col-xs-12" data-url-info="<?= Url::to(['modal/details']);?>">
		<?= ListView::widget([
			'dataProvider' => $dataProvider,
			'layout' => 
				Html::tag('div', '{summary}', ['class' => 'col-xs-12']) 
				. Html::tag('div', '{pager}', ['class' => 'col-xs-12'])
				. Html::tag('div', '{items}', ['class' => 'col-xs-12 col-sm-8 items'])
				. Html::tag(
					'div', 
					Html::tag('div', '', ['id' => 'fileinfo']),
					['class' => 'col-xs-12 col-sm-4']
				)
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