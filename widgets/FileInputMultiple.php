<?php
namespace vommuan\filemanager\widgets;

use yii\helpers\Html;

/**
 * Class FileInputMultiple
 *
 * Basic example of usage:
 * 
 * ```php
 * <?= FileInputMultiple::widget([
 *     'name' => 'mediafile',
 *     'buttonTag' => 'button',
 *     'buttonName' => 'Browse',
 *     'buttonOptions' => ['class' => 'btn btn-default'],
 *     'options' => ['class' => 'form-control'],
 *     // Widget template
 *     'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
 *     // Optional, if set, in container will be inserted selected image
 *     'imageContainer' => '.img',
 *     // Default to FileInput::DATA_URL. This data will be inserted in input field
 *     'pasteData' => FileInput::DATA_URL,
 *     // JavaScript function, which will be called before insert file data to input.
 *     // Argument data contains file data.
 *     // data example: [alt: "Witch with cat", description: "123", url: "/uploads/2014/12/cats-100x100.jpeg", id: "45"]
 *     'callbackBeforeInsert' => 'function(e, data) {
 *         console.log( data );
 *     }',
 * ]) ?>
 * ```
 *
 * This class provides filemanager usage. You can optional select all media file info to your input field.
 * More samples of usage see on github: https://github.com/vommuan/yii2-filemanager
 *
 * @package vommuan\filemanager\widgets
 * @author Michael Naumov <vommuan@gmail.com>
 */
class FileInputMultiple extends FileInput
{
    /**
     * Runs the widget
     * REWRITE
     */
    public function run()
    {
        if (!empty($this->callbackBeforeInsert)) {
            $this->view->registerJs(
				"$('#{$this->options['id']}').on('fileInsert', {$this->callbackBeforeInsert});"
			);
        }

        return $this->render('modal', [
			'input' => $this->renderInput(),
			'data' => [
				'input-id' => $this->options['id'],
				'image-container' => $this->imageContainer,
				'paste-data' => $this->pasteData,
			],
        ]);
    }
}