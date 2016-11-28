<?php

use yii\helpers\Html;

?>

<a href="#" class="thumbnail media-file__link">
	<?= Html::img($model->getIcon($bundle->baseUrl), ['class' => 'media-file__image']);?>
	<div class="file-gallery__checker">
		<span class="glyphicon glyphicon-check"></span>
	</div>
</a>