<?php

use vommuan\filemanager\assets\FileInputAsset;
use vommuan\filemanager\widgets\FileManager;

FileInputAsset::register($this);
?>

<div class="input-widget-form">
	<?= $input;?>

	<div class="filemanager-modal modal fade" id="<?= $widgetId;?>" data-cropper-options='<?= $cropperOptions;?>'>
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!--div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div-->
				<div class="modal-body">
					<?= FileManager::widget([
						'multiple' => $multiple,
					]);?>
				</div>
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
</div>