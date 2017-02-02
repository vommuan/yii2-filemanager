<?php

use vommuan\filemanager\assets\PageHeaderAsset;

PageHeaderAsset::register($this);
?>

<div class="page-header">
	<div class="page-header__icon">
		<?php
		if (isset($icon)) :?>
			<span class="glyphicon glyphicon-<?= $icon;?>"></span>
			<?php
		endif;?>
	</div>
	
	<div class="page-header__title"><?= $title;?></div>
</div>