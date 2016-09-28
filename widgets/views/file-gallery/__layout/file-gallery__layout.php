<?php
use yii\helpers\Url;
?>
<div class="row file-gallery__layout">
	<div class="col-xs-12">{summary}</div>
	<div class="col-xs-12">{pager}</div>
	<div class="col-xs-12 col-sm-8 gallery-items" data-next-page-file-url="<?= Url::to(['next-page-file']);?>">{items}</div>
	<div class="col-xs-12 col-sm-4" id="fileinfo"></div>
	<div class="col-xs-12">{pager}</div>
</div>