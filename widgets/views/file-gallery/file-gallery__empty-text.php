<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\Module;

?>

<div class="row file-gallery__layout">
	<div class="col-xs-12 col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="summary">Показаны записи <b>0</b> из <b>0</b>.</div>
			</div>
			<div class="col-xs-12">
				<?php
				$this->beginBlock('empty-text-pagination', true);?>
					<ul class="pagination">
						<li class="first disabled">
							<span>
								<span class="glyphicon glyphicon-fast-backward"></span>
							</span>
						</li>
						<li class="prev disabled">
							<span>
								<span class="glyphicon glyphicon-backward"></span>
							</span>
						</li>
						<li class="active">
							<?= Html::a(
								1, 
								Url::to([
									'/' . Module::getInstance()->uniqueId . '/file/index',
									'page' => 1,
								]), [
									'data' => [
										'page' => 0,
									],
								]
							);?>
						</li>
						<li class="next disabled">
							<span>
								<span class="glyphicon glyphicon-forward"></span>
							</span>
						</li>
						<li class="last disabled">
							<span>
								<span class="glyphicon glyphicon-fast-forward"></span>
							</span>
						</li>
					</ul>
					<?php
				$this->endBlock();?>
			</div>
			<?= Html::tag(
				'div',
				'',
				[
					'class' => 'col-xs-12 gallery-items',
					'data' => [
						'next-page-file-url' => Url::to(['/' . Module::getInstance()->uniqueId . '/file/next-page-file']),
					],
				]
			);?>
			<div class="col-xs-12">
				<?= $this->blocks['empty-text-pagination'];?>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4" id="fileinfo"></div>
</div>
