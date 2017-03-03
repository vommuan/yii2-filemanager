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
				<div class="modal-body">
					<?= FileManager::widget([
						'multiple' => $multiple,
					]);?>
				</div>
			</div>
		</div>
	</div>
</div>