<?php

use yii\widgets\ListView;

?>

<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'emptyText' => '',
	'layout' => '{items}',
	'itemOptions' => ['tag' => false],
	'itemView' => function ($model, $key, $index, $widget) {
		return $this->render('media-file', [
			'model' => $model,
		]);
	},
]);?>
