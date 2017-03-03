<?php

use vommuan\filemanager\Module;
use yii\helpers\Html;

/** @var \dosamigos\fileupload\FileUploadUI $this */

$context = $this->context;
?>

<!-- The file upload form used as target for the file upload widget -->
<?= Html::beginTag('div', $context->options);?>
	<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
	<div class="fileupload-buttonbar">
		<div class="fileupload-buttonbar__buttons">
			<!-- The fileinput-button span is used to style the file input field as button -->
			<span class="btn btn-primary fileinput-button">
				<span><?= Module::t('main', 'Upload files');?></span>

				<?php
				if ($context->model instanceof \yii\base\Model && null !== $context->attribute) :?>
					<?= Html::activeFileInput($context->model, $context->attribute, $context->fieldOptions);?>
					<?php
				else :?>
					<?= Html::fileInput($context->name, $context->value, $context->fieldOptions);?>
					<?php
				endif;?>
			</span>
		</div>
		<!-- The global progress state -->
		<div class="fileupload-progress fade">
			<!-- The global progress bar -->
			<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
				<div class="progress-bar progress-bar-success" style="width:0%;"></div>
			</div>
			<!-- The extended global progress state -->
			<div class="progress-extended">&nbsp;</div>
		</div>
	</div>
<?= Html::endTag('div');?>
