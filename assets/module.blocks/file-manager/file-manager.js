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
	
	function markFiles() {
		_selectedFiles.forEach(function(item) {
			var checkedItem = _gallery.find('.media-file[data-key="' + item + '"] .media-file__link').eq(0);
			selectFile(checkedItem);
		});
	}
	
	function initSelectedFiles() {
		unselectFiles();
		
		if (undefined == _input || '' == _input.val()) {
			_selectedFiles = [];
			return;
		}
		
		if (_multiple) {
			_selectedFiles = JSON.parse(_input.val());
		} else {
			_selectedFiles = [Number(_input.val())];
		}
		
		markFiles();
	}
	
	function init(initConfig) {
		_widget = initConfig.widget;
		_input = initConfig.input;
		_imageContainer = initConfig.imageContainer;
		_modalView = initConfig.modalView;
		
		_gallery = _widget.find('.gallery').eq(0);
		_pager = (new GalleryPager()).init(_gallery);
		_summary = (new GallerySummary()).init(_gallery, _pager);
		_multiple = _gallery.data('multiple');
		
		initSelectedFiles();
		
		_widget.on('click', '.pagination a', paginationClick);
		_widget.on('click', '.media-file__link', mediaFileLinkClick);
		_widget.on('click', '[role="delete"]', deleteFileClick);
		_widget.on('click', '.insert-btn', insertButtonClick);
		_widget.on('submit', '.control-form', submitButtonClick);
		
		return this;
	}
	
	function paginationClick(event) {
		event.preventDefault();
		
		var link = $(event.currentTarget);
		
		_gallery.find('.gallery__items').load(link.attr('href'), markFiles);
		_pager.click(link);
	}
	
	function toggleSelectedFiles(item) {
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
		(new FileGallery()).init(_gallery).click(item);
	}
	
	function unselectFiles() {
		(new FileGallery()).init(_gallery).uncheckAll();
	}
	
	function mediaFileLinkClick(event) {
		event.preventDefault();
		
		var item = $(event.currentTarget);
		
		selectFile(item);
		toggleSelectedFiles(item);
	}

	function uploadFromNextPage() {
		$.ajax({
			type: "POST",
			data: 'page=' + _pager.getCurrentPage(),
			url: _gallery.data('next-page-file-url'),
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				_gallery.find('.gallery-items').eq(0).append(response.html);
				
				unselectFiles();
				markFiles();
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
				
				_gallery.closest('.file-manager__content').find('.file-details').empty();
			},
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				$('[data-key="' + response.id + '"]').fadeOut(function() {
					$(this).remove();
					uploadFromNextPage();
					_pager.update(response.pagination);
					_summary.update(response.pagination);
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
		
		if (false == _multiple) {
			_input.val(_selectedFiles[0]);
		} else {
			_input.val(JSON.stringify(_selectedFiles));
		}
		
		_input.trigger("fileInsert", _selectedFiles);
		
		if (_imageContainer) {
			_imageContainer.empty();
			
			_imageContainer.load(_gallery.data('insert-files-load'), {
				'selectedFiles': JSON.stringify(_selectedFiles)
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
                (new FileGallery()).init(_gallery).setAjaxLoader();
            },
            success: function(html) {
                _gallery.closest('.file-manager__content').find('.file-details').html(html);
            }
        });
	}
	
	return {
		'init': init,
		'initSelectedFiles': initSelectedFiles
	};
}