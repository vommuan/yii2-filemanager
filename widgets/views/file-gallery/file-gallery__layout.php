<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\Module;

?>

<div class="row file-gallery__layout">
	<div class="col-xs-12 col-sm-8">
		<div class="row">
			<div class="col-xs-12">{summary}</div>
			<div class="col-xs-12">{pager}</div>
			<?= Html::tag(
				'div',
				'{items}',
				[
					'class' => 'col-xs-12 gallery-items',
					'data' => [
						'next-page-file-url' => Url::to(['/' . Module::getInstance()->uniqueId . '/file/next-page-file']),
					],
				]
			);?>
			<div class="col-xs-12">{pager}</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4" id="fileinfo"></div>
</div>