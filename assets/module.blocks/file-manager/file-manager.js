function FileManager(config) {
	'use strict';
	
	var widget = config.widget,
		input = config.input,
		imageContainer = config.imageContainer,
		modalView = config.modalView,
		cropperOptions = widget.data('cropper-options'),
		editedImageId = null,
		
		manager = widget.find('.file-manager').eq(0),
		gallery = new FileGallery(widget.find('.gallery').eq(0)),
		fileDetails = widget.find('.file-details').eq(0),
		
		ajaxRequest = null;
	
	widget.on('show.bs.modal', {'input': input}, gallery.initSelectedFiles);
	widget.on('click', '.insert-button', insertButtonClick);
	widget.on('click', '.controls-item_edit', loadImageEditForm);
	widget.on('click', '.controls-item_delete', deleteFileClick);
	widget.on('blur', '.description-field__input .form-control', saveFileDetails);
	widget.on('selectItem.fm', '.media-file', loadDetails);
	widget.on('click', '.main-controls__control_cancel', toggleViewMode);
	widget.on('submit', '.image-edit-form', saveEditedImage);
	
	function setAjaxLoader() {
		fileDetails.html(
			$('<div/>', {
				'class': 'loading',
				'html': '<span class="glyphicon glyphicon-refresh spin"></span>'
			})
		);
	}
	
	function insertButtonClick(event) {
		if (undefined == input || undefined == modalView) {
			console.error('Error. FileManager.insertButtonClick(): check all defined variables.');
			return;
		}
		
		if (gallery.multiple) {
			input.val(JSON.stringify(gallery.getSelectedFilesId()));
		} else {
			input.val(gallery.getSelectedFilesId()[0]);
		}
		
		input.trigger('beforeInsert.fm', gallery.getSelectedFilesId());
		
		if (imageContainer) {
			imageContainer.empty();
			
			imageContainer.load(manager.data('base-url') + '/insert-files-load', {
				'selectedFiles': JSON.stringify(gallery.getSelectedFilesId()),
				'imageOptions': widget.closest('.input-widget-form').find('[role="clear-input"]').eq(0).data('image-options')
			}, function () {
				imageContainer.trigger('afterInsert.fm', gallery.getSelectedFilesId());
			});
		}
		
		event.preventDefault();
		
		modalView.hide();
	}
	
	function toggleViewMode() {
		manager.find('.mode__block').toggleClass('mode__block_hide');
	}
	
	function loadImageEditForm(event) {
		var editLink = $(event.currentTarget);
		editedImageId = editLink.closest('.thumbnail-block').find('.thumbnail-block__image').eq(0).data('key');
		
		toggleViewMode();
		
		$.post(editLink.attr('href'), function (response) {
			manager.find('.mode__block_edit').html(response);
			cropperInit(cropperOptions);
		});
		
		event.preventDefault();
	}
	
	function saveEditedImage(event) {
        var form = $(event.currentTarget);
		
        $.post(form.attr('action'), form.serialize(), function (response) {
			updateThumbnails();
			toggleViewMode();
		});
		
		event.preventDefault();
	}
	
	function updateThumbnails() {
		$.post(manager.data('base-url') + '/thumb-url', {'id': editedImageId}, function (url) {
			if ('' == url) {
				var mediaFile = widget.find('.gallery .media-file[data-key="' + editedImageId + '"]');
				
				mediaFile.find('.media-file__link').eq(0).trigger('click');
				mediaFile.remove();
			} else {
				widget.find('.gallery .media-file[data-key="' + editedImageId + '"] img').attr('src', url);
				fileDetails.find('.thumbnail-block__image img').attr('src', url);
			}
		});
	}

	function deleteFileClick(event) {
		event.preventDefault();
		
		var deleteLink = $(event.currentTarget);
		var confirmMessage = deleteLink.data('message');

		$.ajax({
			type: 'POST',
			url: deleteLink.attr('href') + '&page=' + gallery.pager.getCurrentPage(),
			beforeSend: function() {
				if (!confirm(confirmMessage)) {
					return false;
				}
				
				fileDetails.empty();
			},
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				gallery.deleteItem(response.id, response.pagination);
			}
		});
	}
	
	function saveFileDetails(event) {
        var form = $(event.currentTarget).closest('.details-form');

        setAjaxLoader();
        
        $.post(form.attr('action'), form.serialize(), function(response) {
			fileDetails.html(response);
		});
	}
	
	function loadDetails(event) {
		if (ajaxRequest) {
			ajaxRequest.abort();
			ajaxRequest = null;
		}
		
		var requestParams = {
			type: 'GET',
			url: manager.data('base-url') + '/details',
			beforeSend: setAjaxLoader,
			success: function(html) {
				fileDetails.html(html);
			}
		};
		
		var item = $(event.currentTarget);
		
		if (gallery.isSelected(item)) {
			requestParams.data = 'id=' + item.data('key');
			ajaxRequest = $.ajax(requestParams);
		} else if (gallery.getSelectedItems().length) {
			requestParams.data = 'id=' + gallery.getSelectedItems().filter(':last').data('key');
			ajaxRequest = $.ajax(requestParams);
		} else {
			fileDetails.empty();
		}
	}
}