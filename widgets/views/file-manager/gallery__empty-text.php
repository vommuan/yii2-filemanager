<?php

use vommuan\filemanager\Module;
use yii\data\Pagination;
use yii\widgets\LinkPager;

?>

<div class="gallery__items gallery-items" id="<?= $galleryItemsId;?>"></div>
<div class="gallery__summary">
	<div class="summary">
		<?= Module::t('main', 'Showing files <b>0</b> of <b>0</b>.');?>
	</div>
</div>
<div class="gallery__pager">
	<?= LinkPager::widget(
		array_merge(
			$pagerParams, [
				'pagination' => new Pagination([
					'route' => '/' . Module::getInstance()->uniqueId . '/file/page',
					'totalCount' => 1
				]), // if point 0 intead of 1, pages not be displayed
			]
		)
	);?>
</div>