<?php

use yii\helpers\Html;
use yii\helpers\Url;
use vommuan\filemanager\Module;

$itemsId = $modalId . '_gallery-items';
$nextPageFileUrl = Url::to(['/' . Module::getInstance()->uniqueId . '/file/next-page-file']);

?>

<div class="gallery__summary">{summary}</div>
<div class="gallery__pager">{pager}</div>
<div class="gallery__items gallery-items" id="<?= $itemsId;?>" data-next-page-file-url="<?= $nextPageFileUrl;?>">{items}</div>
<div class="gallery__pager">{pager}</div>