function FileManager() {
	'use strict';
	
	var _widget;
	var _input;
	var _imageContainer;
	var _modalView;
	var _gallery;
	var _pager;
	var _summary;
	var _selectedFiles;
	var _multiple = false;
	
	function initSelectedFiles() {
		if (_multiple) {
			_selectedFiles = JSON.parse(_input.val());
		} else {
			_selectedFiles = [Number(_input.val())];
		}
		
		_selectedFiles.forEach(function(item) {
			var checkedItem = _gallery.find('.media-file[data-key="' + item + '"] .media-file__link').eq(0);
			selectFile(checkedItem);
		});
	}
	
	function init(initConfig) {
		_widget = initConfig.widget;
		_input = initConfig.input;
		_imageContainer = initConfig.imageContainer;
		_modalView = initConfig.modalView;
		
		_gallery = _widget.find('.file-gallery').eq(0);
		_pager = (new GalleryPager()).init(_gallery);
		_summary = (new GallerySummary()).init(_gallery, _pager);
		_multiple = _gallery.data('multiple');
		
		initSelectedFiles();
		
		_widget.on('click', '.media-file__link', mediaFileLinkClick);
		_widget.on('click', '[role="delete"]', deleteFileClick);
		_widget.on('click', '.insert-btn', insertButtonClick);
		_widget.on('submit', '.control-form', submitButtonClick);
		
		return this;
	}
	
	function toogleSelectedFiles(item) {
		var imageId = item.closest('.media-file').data('key');
		
		if (_multiple) {
			var imageIdIndex = _selectedFiles.indexOf(imageId);
			
			if (-1 == imageIdIndex) { // not found
				_selectedFiles.push(imageId);
			} else {
				_selectedFiles.splice(imageIdIndex, 1);
			}
		} else {
			_selectedFiles[0] = imageId;
		}
	}
	
	function selectFile(item) {
		(new MediaFile()).init(item, _multiple).click();
		(new FileGallery()).init(_gallery).itemClick(item);
	}
	
	function mediaFileLinkClick(event) {
		event.preventDefault();
		
		var item = $(event.currentTarget);
		
		selectFile(item);
		toogleSelectedFiles(item);
	}

	function uploadFromNextPage() {
		$.ajax({
			type: "POST",
			data: 'page=' + _pager.getCurrentPage(),
			url: _gallery.find('.gallery-items').eq(0).data('next-page-file-url'),
			success: function(response) {
				if (!response.success) {
					return;
				}
				_gallery.find('.gallery-items').eq(0).append(response.html);
			}
		});
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
				
				(new FileGallery()).init(_gallery).setAjaxLoader();
			},
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				$(_gallery.data('details-target')).html('');
				$('[data-key="' + response.id + '"]').fadeOut(function() {
					$(this).remove();
					uploadFromNextPage();
				});
				
				_pager.update(response.pagination);
				_summary.update(response.pagination);
			}
		});
	}
	
	function insertButtonClick(event) {
		event.preventDefault();
		
		if (false == _multiple) {
			_input.val(_selectedFiles[0]);
		} else {
			_input.val(JSON.stringify(_selectedFiles));
		}
		
		/*var data = _widget.find('.media-file__link_checked img');
		
		_input.trigger("fileInsert", [data]);

		if (_imageContainer) {
			_imageContainer.empty();
			
			for (var i = 0; i < data.length; i++) {
				_imageContainer.append(
					$('<img/>', {
						src: data.eq(i).attr('src'),
						alt: data.eq(i).attr('alt'),
						class: 'selected-image'
					})
				);
			};
		}
		
		if (false == _multiple) {
			_input.val(data.eq(0).closest('.media-file').data('key'));
		} else {
			var inputData = [];
			
			for (var i = 0; i < data.length; i++) {
				inputData[i] = data.eq(i).closest('.media-file').data('key');
			}
			
			_input.val(JSON.stringify(inputData));
		}*/
		
		_modalView.hide();
	}
	
	function submitButtonClick(event) {
		event.preventDefault();
        
        var submitForm = $(event.currentTarget);
        var fileInfo = _gallery.data('details-target');

        $.ajax({
            type: "POST",
            url: submitForm.attr("action"),
            data: submitForm.serialize(),
            beforeSend: function() {
                (new FileGallery()).init(_gallery).setAjaxLoader();
            },
            success: function(html) {
                $(fileInfo).html(html);
            }
        });
	}
	
	return {
		'init': init
	};
}