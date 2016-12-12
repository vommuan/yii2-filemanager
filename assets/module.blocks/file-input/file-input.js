function FileInputWidget() {
	'use strict';
	
	var _widget;
	var _fileManager;
	
	function init(initConfig) {
		_widget = initConfig.widget;
		initConfig.modalView = this;
		
		if (undefined == _fileManager) {
			_fileManager = (new FileManager()).init(initConfig);
		}
		
		return this;
	}
	
	function show() {
		if (undefined == _widget) {
			console.log('Warning. Call init() before show().');
			return this;
		}
		
		_fileManager.initSelectedFiles();
		_widget.modal('show');
		
		return this;
	}
	
	function hide() {
		if (undefined == _widget) {
			console.log('Warning. Call init() before hide().');
			return this;
		}
		
		_widget.modal("hide");
		
		return this;
	}
	
	return {
		'init': init,
		'show': show,
		'hide': hide
	};
}

function ImageContainer() {
	'use strict';
	
	var _form;
	var _widget;
	var _gallery;
	var _container;
	var _defaultImageUrl;
	
	function initFiles() {
		var input = _form.find('.input-widget-form__input').eq(0);
		
		if (undefined == input || '' == input.val()) {
			setDefault();
			return;
		}
		
		var multiple = _gallery.data('multiple');
		
		if (multiple) {
			var files = JSON.parse(input.val());
		} else {
			var files = [Number(input.val())];
		}
		
		if (files.length && _container) {
			_container.load(_gallery.data('insert-files-load'), {
				'selectedFiles': JSON.stringify(files)
			}, function(data) {
				_container.trigger('initFiles');
			});
		}
	}
	
	function init(form) {
		_form = form;
		_widget = $(_form.find('[role="filemanager-launch"]').data('target'));
		_gallery = _widget.find('.gallery').eq(0)
		
		_container = $(_form.find('[role="clear-input"]').eq(0).data('image-container'));
		_defaultImageUrl = _form.find('[role="clear-input"]').eq(0).data('default-image');
		
		initFiles();
		
		return this;
	}
	
	function setDefault() {
		if (!$(_container).length) {
			return;
		}
		
		_container.empty();
		
		if ('' != _defaultImageUrl) {
			var defaultImage = $('<img/>', {'src': _defaultImageUrl, 'alt': ''});
			
			_container.append(defaultImage);
		}
	}
	
	return {
		'init': init,
		get container() {
			return _container;
		},
		'setDefault': setDefault
	}
}

function InputForm() {
	'use strict';
	
	var _form;
	var _imageContainer;
	var _fileInputWidget;
	
	function init(form) {
		_form = form;
		_imageContainer = (new ImageContainer()).init(_form);
		
		_form.on('click', '[role="filemanager-launch"]', launchButtonClick);
		_form.on('click', '[role="clear-input"]', clearButtonClick);
		
		return this;
	}
	
	function launchButtonClick(event) {
		event.preventDefault();
		
		var launchButton = $(event.currentTarget);
		
		if (undefined == _fileInputWidget) {
			_fileInputWidget = (new FileInputWidget()).init({
				'widget': $(launchButton.data('target')),
				'input': _form.find('.input-widget-form__input').eq(0),
				'imageContainer': _imageContainer.container
			}).show();
		} else {
			_fileInputWidget.show();
		}
	}
	
	function clearInput(input) {
		input.val('');
	}
	
	function clearButtonClick(event) {
		event.preventDefault();
		
		var clearButton = $(event.currentTarget);
		var input = $("#" + clearButton.data('clear-element-id'));
		
		clearInput(input);
		_imageContainer.setDefault();
	}
	
	return {
		'init': init
	};
}

$(document).ready(function() {
	'use strict';
	
	$('.input-widget-form').each(function(index, element) {
		var form = $(element);
		
		(new InputForm()).init(form);
	});
});