function FileManager(config) {
	'use strict';
	
	var widget = config.widget,
		input = config.input,
		imageContainer = config.imageContainer,
		modalView = config.modalView,
		
		manager = widget.find('.file-manager').eq(0),
		gallery = new FileGallery(widget.find('.gallery').eq(0)),
		fileDetails = widget.find('.file-details').eq(0),
		
		ajaxRequest = null;
	
	widget.on('show.bs.modal', {'input': input}, gallery.initSelectedFiles);
	widget.on('click', '[role="delete"]', deleteFileClick);
	widget.on('click', '.insert-btn', insertButtonClick);
	widget.on('submit', '.control-form', submitButtonClick);
	widget.on('selectItem.fm', '.media-file', loadDetails);
	
	function setAjaxLoader() {
		fileDetails.html(
			$('<div/>', {
				'class': 'loading',
				'html': '<span class="glyphicon glyphicon-refresh spin"></span>'
			})
		);
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
				cropperInit();
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
		
		input.trigger('fileInsert', gallery.getSelectedFilesId());
		
		if (imageContainer) {
			imageContainer.empty();
			
			imageContainer.load(manager.data('base-url') + '/insert-files-load', {
				'selectedFiles': JSON.stringify(gallery.getSelectedFilesId()),
				'imageOptions': widget.closest('.input-widget-form').find('[role="clear-input"]').eq(0).data('image-options')
			});
		}
		
		modalView.hide();
	}
	
	function submitButtonClick(event) {
		event.preventDefault();
        
        var submitForm = $(event.currentTarget);

        $.ajax({
            type: 'POST',
            url: submitForm.attr('action'),
            data: submitForm.serialize(),
            beforeSend: function() {
                gallery.setAjaxLoader();
            },
            success: function(html) {
                fileDetails.html(html);
                cropperInit();
            }
        });
	}
}