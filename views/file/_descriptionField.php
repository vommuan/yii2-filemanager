<div class="description-field">
	<div class="description-field__title description-title">
		<div class="description-title__label">{label}</div>
		<div class="description-title__status status text-success">
			<?php
			if ('' !== Yii::$app->session->getFlash('mediaFileUpdateResult', '')) : ?>
				<span class="status__message"><?= Yii::$app->session->getFlash('mediaFileUpdateResult'); ?></span>
				<span class="status__icon glyphicon glyphicon-ok"></span>
				<?php
			endif;?>
		</div>
	</div>
	<div class="description-field__input">{input}</div>
	{hint}{error}
</div>