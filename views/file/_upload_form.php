<?php
use vommuan\filemanager\Module;
use yii\helpers\Html;

/** @var \dosamigos\fileupload\FileUploadUI $this */
?>

<!-- The file upload form used as target for the file upload widget -->
<?= Html::beginTag('div', $this->context->options); ?>
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="col-lg-12">
			<div class='btn-group' role="group">
				<span class="btn btn-success fileinput-button">
					<i class="glyphicon glyphicon-plus"></i>
					<?php 
					if ($this->context->model instanceof \yii\base\Model && $this->context->attribute !== null) {
						echo Html::activeFileInput(
							$this->context->model,
							$this->context->attribute,
							$this->context->fieldOptions
						);
					} else {
						echo Html::fileInput(
							$this->context->name,
							$this->context->value,
							$this->context->fieldOptions
						);
					}
					?>
				</span>
				<?php
				if (!Module::getInstance()->autoUpload) :?>
					<span class="btn btn-primary start">
						<i class="glyphicon glyphicon-upload"></i>
					</span>
					<?php
				endif;?>
				<span class="btn btn-warning cancel">
					<i class="glyphicon glyphicon-ban-circle"></i>
				</span>
				<span class="btn btn-danger delete">
					<i class="glyphicon glyphicon-trash"></i>
				</span>
			</div>
			<input type="checkbox" class="toggle">
            <!-- The global file processing state -->
            <span class="fileupload-process"></span>
        </div>
        
        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
<?= Html::endTag('div');?>
