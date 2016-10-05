<?php
namespace vommuan\filemanager\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Class FileInput
 *
 * Basic example of usage:
 * 
 * ```php
 * <?= FileInput::widget([
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
 * @author Zabolotskikh Boris <zabolotskich@bk.ru>
 */
class FileInput extends InputWidget
{
    /**
     * @var string widget template
     */
    public $template = '<div class="input-widget-form input-group">{input}<span class="input-group-btn">{button}{reset-button}</span></div>';

    /**
     * @var string button tag
     */
    public $buttonTag = 'button';

    /**
     * @var string button name
     */
    public $buttonName = 'Browse';

    /**
     * @var array button html options
     */
    public $buttonOptions = ['class' => 'btn btn-default'];

    /**
     * @var string reset button tag
     */
    public $resetButtonTag = 'button';

    /**
     * @var string reset button name
     */
    public $resetButtonName = '<span class="text-danger glyphicon glyphicon-remove"></span>';

    /**
     * @var array reset button html options
     */
    public $resetButtonOptions = ['class' => 'btn btn-default'];

    /**
     * @var string Optional, if set, in container will be inserted selected image
     */
    public $imageContainer = '';
	
	/**
	 * @var string default image url
	 */
	public $defaultImage = '';
	
    /**
     * @var string JavaScript function, which will be called before insert file data to input.
     * Argument data contains file data.
     * data example: [alt: "Witch with cat", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
     */
    public $callbackBeforeInsert = '';

    protected $defaultOptions = ['class' => 'input-widget-form__input form-control'];
    
    /**
     * @boolean One or more insert images
     */
    public $multiple = false;
    
    protected function initOptions()
    {
		if (!empty($this->options['class'])) {
			$this->options['class'] = $this->defaultOptions . ' ' . $this->options['class'];
		} else {
			$this->options['class'] = $this->defaultOptions;
		}
	}
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->initOptions();

        if (empty($this->buttonOptions['id'])) {
            $this->buttonOptions['id'] = $this->options['id'] . '-btn';
        }

        $this->buttonOptions['role'] = 'filemanager-launch';
        $this->buttonOptions['data-toogle'] = 'modal';
        $this->buttonOptions['data-target'] = '#' . $this->id;
        $this->resetButtonOptions['role'] = 'clear-input';
        $this->resetButtonOptions['data-clear-element-id'] = $this->options['id'];
        $this->resetButtonOptions['data-image-container'] = $this->imageContainer;
        $this->resetButtonOptions['data-default-image'] = $this->defaultImage;
        
        $this->multiple = ($this->multiple) ? 'true' : 'false';
    }

	protected function renderInput()
	{
		$replace = [];
		
		if ($this->hasModel()) {
            $replace['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $replace['{input}'] = Html::textInput($this->name, $this->value, $this->options);
        }

        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonName, $this->buttonOptions);
        $replace['{reset-button}'] = Html::tag($this->resetButtonTag, $this->resetButtonName, $this->resetButtonOptions);
		
		return strtr($this->template, $replace);
	}

    /**
     * Runs the widget.
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
			'widgetId' => $this->id,
			'multiple' => $this->multiple,
        ]);
    }
}