function FileManager() {
	'use strict';
	
	var _widget;
	var _input;
	var _imageContainer;
	var _modalView;
	
	var _gallery;
	var _fileGallery;
	var _fileDetails;
	var _ajaxRequest = null;
	
	function initSelectedFiles() {
		_fileGallery.initSelectedFiles(_input);
	}
	
	function init(config) {
		_widget = config.widget;
		_input = config.input;
		_imageContainer = config.imageContainer;
		_modalView = config.modalView;
		
		_gallery = _widget.find('.gallery').eq(0);
		_fileDetails = _widget.find('.file-details').eq(0);
		_fileGallery = new FileGallery().init(_gallery);
		
		initSelectedFiles();
		
		_widget.on('click', '[role="delete"]', deleteFileClick);
		_widget.on('click', '.insert-btn', insertButtonClick);
		_widget.on('submit', '.control-form', submitButtonClick);
		_widget.on('media-file-click', '.media-file', loadDetails);
		
		return this;
	}
	
	function setAjaxLoader() {
		_fileDetails.html(
			$('<div/>', {
				'class': 'loading',
				'html': '<span class="glyphicon glyphicon-refresh spin"></span>'
			})
		);
	}
	
	function loadDetails(event) {
		if (_ajaxRequest) {
			_ajaxRequest.abort();
			_ajaxRequest = null;
		}
		
		var requestParams = {
			type: "GET",
			url: _gallery.data('base-url') + '/details',
			beforeSend: setAjaxLoader,
			success: function(html) {
				_fileDetails.html(html);
				cropperInit();
			}
		};
		
		var item = $(event.currentTarget);
		
		if (_fileGallery.isChecked(item)) {
			requestParams.data = "id=" + item.closest('.media-file').data("key");
			_ajaxRequest = $.ajax(requestParams);
		} else if (_fileGallery.getCheckedItems().length) {
			requestParams.data = "id=" + _fileGallery.getCheckedItems().filter(':last').data("key");
			_ajaxRequest = $.ajax(requestParams);
		} else {
			_fileDetails.empty();
		}
	}

	function deleteFileClick(event) {
		event.preventDefault();
		
		var deleteLink = $(event.currentTarget);
		var confirmMessage = deleteLink.data("message");

		$.ajax({
			type: "POST",
			url: deleteLink.attr("href"),
			beforeSend: function() {
				if (!confirm(confirmMessage)) {
					return false;
				}
				
				_fileDetails.empty();
			},
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				$('[data-key="' + response.id + '"]').fadeOut(function() {
					$(this).remove();
					_fileGallery.uploadFromNextPage();
					_fileGallery.getPager().update(response.pagination);
					_fileGallery.getSummary().update(response.pagination);
				});
			}
		});
	}
	
	function insertButtonClick(event) {
		event.preventDefault();
		
		if (undefined == _input || undefined == _modalView) {
			console.error('Error. FileManager.insertButtonClick(): check all defined variables.');
			return;
		}
		
		if (_fileGallery.isMultiple()) {
			_input.val(JSON.stringify(_fileGallery.getSelectedFiles()));
		} else {
			_input.val(_fileGallery.getSelectedFiles()[0]);
		}
		
		_input.trigger("fileInsert", _fileGallery.getSelectedFiles());
		
		if (_imageContainer) {
			_imageContainer.empty();
			
			_imageContainer.load(_gallery.data('base-url') + '/insert-files-load', {
				'selectedFiles': JSON.stringify(_fileGallery.getSelectedFiles()),
				'imageOptions': _widget.closest('.input-widget-form').find('[role="clear-input"]').eq(0).data('image-options')
			});
		}
		
		_modalView.hide();
	}
	
	function submitButtonClick(event) {
		event.preventDefault();
        
        var submitForm = $(event.currentTarget);

        $.ajax({
            type: "POST",
            url: submitForm.attr("action"),
            data: submitForm.serialize(),
            beforeSend: function() {
                _fileGallery.setAjaxLoader();
            },
            success: function(html) {
                _fileDetails.html(html);
                cropperInit();
            }
        });
	}
	
	return {
		'init': init,
		'initSelectedFiles': initSelectedFiles
	};
}