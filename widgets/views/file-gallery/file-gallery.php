<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="file-gallery" data-details-url="<?= Url::to(['details']);?>">
	<?= ListView::widget([
		'dataProvider' => $dataProvider,
		'layout' => $this->render('__layout/file-gallery__layout'),
		'itemOptions' => [
			'class' => 'col-xs-4 col-sm-2 gallery-items__item',
		],
		'itemView' => function ($model, $key, $index, $widget) {
			return Html::a(
				Html::img($model->getIcon($this->params['moduleBundle']->baseUrl))
					. Html::tag('span', '', ['class' => 'glyphicon glyphicon-check checked']),
				'#mediafile', [
					'class' => 'thumbnail',
				]
			);
		},
	]);?>
</div>