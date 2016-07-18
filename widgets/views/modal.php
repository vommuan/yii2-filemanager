<?php
use vommuan\filemanager\assets\ModalAsset;
use vommuan\filemanager\assets\FilemanagerAsset;

ModalAsset::register($this);
$this->params['moduleBundle'] = FilemanagerAsset::register($this);
?>

<div role="filemanager-modal" class="modal" tabindex="-1"
     data-frame-id="<?= $frameId ?>"
     data-frame-src="<?= $frameSrc ?>"
     data-btn-id="<?= $btnId ?>"
     data-input-id="<?= $inputId ?>"
     data-image-container="<?= isset($imageContainer) ? $imageContainer : '' ?>"
     data-paste-data="<?= isset($pasteData) ? $pasteData : '' ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>