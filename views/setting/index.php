<?php
use yii\helpers\Html;
use kartik\alert\Alert;
use vommuan\filemanager\Module;
use vommuan\filemanager\widgets\PageHeader;
use vommuan\filemanager\assets\SettingAsset;

/* @var $this yii\web\View */

SettingAsset::register($this);

$this->title = Module::t('main', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Module::t('main', 'File manager'), 'url' => ['file/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="filemanager-default-settings">
	<?= PageHeader::widget([
		'icon' => 'wrench',
		'title' => $this->title,
	]);?>
	
    <div class="panel panel-default">
        <div class="panel-heading"><?= Module::t('main', 'Thumbnails settings') ?></div>
        <div class="panel-body">
            <?php 
            if (Yii::$app->session->getFlash('successResize')) {
                echo Alert::widget([
                    'type' => Alert::TYPE_SUCCESS,
                    'title' => Module::t('main', 'Thumbnails sizes has been resized successfully!'),
                    'icon' => 'glyphicon glyphicon-ok-sign',
                    'body' => Module::t('main', 'Do not forget every time you change thumbnails presets to make them resize.'),
                    'showSeparator' => true,
                ]);
			}
			?>
            <p><?= Module::t('main', 'Now using next thumbnails presets');?>:</p>
            <ul>
                <?php 
                foreach (Module::getInstance()->thumbs as $preset) :?>
                    <li><strong><?= $preset['name'] ?>:</strong><?= $preset['size'][0] .' x ' . $preset['size'][1];?></li>
					<?php
				endforeach;?>
            </ul>
            <p>
				<?= Module::t('main', 'If you change the thumbnails sizes, it is strongly recommended to make resize all thumbnails.') ?>
			</p>
            <?= Html::a(Module::t('main', 'Do resize thumbnails'), ['setting/resize'], ['class' => 'btn btn-danger']);?>
        </div>
    </div>
</div>
