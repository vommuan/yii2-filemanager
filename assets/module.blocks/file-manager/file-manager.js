function FileManager(config) {
	'use strict';
	
	var widget = config.widget,
		input = config.input,
		imageContainer = config.imageContainer,
		modalView = config.modalView,
		cropperOptions = widget.data('cropper-options'),
		
		manager = widget.find('.file-manager').eq(0),
		gallery = new FileGallery(widget.find('.gallery').eq(0)),
		fileDetails = widget.find('.file-details').eq(0),
		
		ajaxRequest = null;
	
	widget.on('show.bs.modal', {'input': input}, gallery.initSelectedFiles);
	widget.on('click', '.file-details-form__insert-button', insertButtonClick);
	widget.on('click', '.file-details-form__edit-button', loadImageEditForm);
	widget.on('click', '.file-details-form__delete-button', deleteFileClick);
	widget.on('submit', '.file-details-form', saveFileDetails);
	widget.on('selectItem.fm', '.media-file', loadDetails);
	widget.on('click', '.main-controls__cancel-button', showGalleryBlock);
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
		event.preventDefault();
		
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
		
		modalView.hide();
	}
	
	function loadImageEditForm(event) {
		event.preventDefault();
		
		var button = $(event.currentTarget);
		
		manager.find('.mode__block').toggleClass('mode__block_hide');
		
		$.ajax({
			type: 'GET',
			url: manager.data('base-url') + '/edit',
			data: 'id=' + button.data('key'),
			success: function(response) {
				manager.find('.mode__block_edit').html(response);
				cropperInit(cropperOptions);
			}
		});
	}
	
	function showGalleryBlock(event) {
		event.preventDefault();
		
		manager.find('.mode__block').toggleClass('mode__block_hide');
	}
	
	function saveEditedImage(event) {
		event.preventDefault();
        
        var form = $(event.currentTarget);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                manager.find('.mode__block_edit').html(response);
                cropperInit(cropperOptions);
            }
        });
	}

	function deleteFileClick(event) {
		event.preventDefault();
		
		var deleteLink = $(event.currentTarget);
		var confirmMessage = deleteLink.data('message');

		$.ajax({
			type: 'POST',
			url: deleteLink.attr('href'),
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
		event.preventDefault();
        
        var form = $(event.currentTarget);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            beforeSend: setAjaxLoader,
            success: function(html) {
                fileDetails.html(html);
            }
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