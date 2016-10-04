<?php

use yii\helpers\Html;
use vommuan\filemanager\assets\FileInputAsset;
use vommuan\filemanager\widgets\FileManager;

FileInputAsset::register($this);
?>

<?= $input;?>

<?= Html::beginTag(
	'div',
	[
		'id' => 'filemanager-modal',
		'class' => 'modal fade',
		'data' => $data,
	]
);?>
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h1 class="modal-title"></h1>
			</div>
			<div class="modal-body">
				<?= FileManager::widget([
					'modal' => true,
					'multiple' => $multiple,
				]);?>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
<?= Html::endTag('div');?>