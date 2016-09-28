<?php
//use vommuan\filemanager\assets\ModalAsset;
//use vommuan\filemanager\assets\FileManagerAsset;

use vommuan\filemanager\assets\FileInputAsset;
use vommuan\filemanager\widgets\FileManager;

//ModalAsset::register($this);
//$this->params['moduleBundle'] = FileManagerAsset::register($this);

FileInputAsset::register($this);

?>

<?= $input;?>

<div class="modal fade" id='filemanager-modal'>
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
				]);?>
			</div>
			<div class="modal-footer"></div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->