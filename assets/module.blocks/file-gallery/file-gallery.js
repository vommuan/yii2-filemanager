/**
 * File gallery handler
 */
function FileGallery(galleryBlock) {
	'use strict';
	
	var pager = (new GalleryPager()).init(galleryBlock),
		summary = (new GallerySummary()).init(galleryBlock, pager),
		multiple = galleryBlock.data('multiple'),
		selectedFilesId = [];
		
	galleryBlock.on('click', '.pagination a', paginationClick);
	galleryBlock.on('click', '.media-file__link', itemClick);
	
	function initSelectedFiles(event) {
		var input = event.data.input;
		
		unselectAll();
		
		if (undefined == input || '' == input.val()) {
			return;
		}
		
		if (multiple) {
			selectedFilesId = JSON.parse(input.val());
		} else {
			selectedFilesId = [Number(input.val())];
		}
		
		selectItems();
	}
	
	function paginationClick(event) {
		event.preventDefault();
		
		var link = $(event.currentTarget);
		
		$.ajax({
			type: 'POST',
			url: link.attr('href'),
			success: function(response) {
				galleryBlock.find('.gallery__items').html(response.items);
				summary.update(response.pagination);
			}
		});
		
		pager.click(link);
	}
	
	function itemClick(event) {
		event.preventDefault();
		
		var item = $(event.currentTarget);
		
		selectItem(item);
		toggleSelectedFiles(item);
		
		item.closest('.media-file').trigger('selectItem.fm');
	}
	
	function isSelected(item) {
		return !!item.find('.media-file__link_checked').length;
	}
	
	function getSelectedItems() {
		return galleryBlock.find('.media-file__link_checked').closest('.media-file');
	}
	
	function getSelectedFilesId() {
		return selectedFilesId;
	}
	
	function selectItem(item) {
		if (multiple) {
			item.toggleClass('media-file__link_checked');
		} else if (item.hasClass('media-file__link_checked')) {
			item.removeClass('media-file__link_checked');
		} else {
			unselectAll();
			item.addClass('media-file__link_checked');
		}
	}
	
	function toggleSelectedFiles(item) {
		var fileId = item.closest('.media-file').data('key');
		
		if (multiple) {
			var fileIdIndex = selectedFilesId.indexOf(fileId);
			
			if (-1 == fileIdIndex) { // not found
				selectedFilesId.push(fileId);
			} else {
				selectedFilesId.splice(fileIdIndex, 1);
			}
		} else {
			selectedFilesId[0] = fileId;
		}
	}
	
	function selectItems() {
		selectedFilesId.forEach(function(item) {
			selectItem(galleryBlock.find('.media-file[data-key="' + item + '"] .media-file__link'));
		});
		
		getSelectedItems().filter(':last').trigger('selectItem.fm');
	}
	
	function unselectAll() {
		galleryBlock.find('.media-file__link').removeClass('media-file__link_checked');
	}
	
	function deleteItem(id, pagination) {
		galleryBlock.find('[data-key="' + id + '"]').fadeOut(function() {
			$(this).remove();
			uploadFromNextPage();
			pager.update(pagination);
			summary.update(pagination);
		});
	}
	
	function uploadFromNextPage() {
		$.ajax({
			type: "POST",
			data: 'page=' + pager.getCurrentPage(),
			url: galleryBlock.closest('.file-manager').data('base-url') + '/next-page-file',
			success: function(response) {
				if (!response.success) {
					return;
				}
				
				galleryBlock.find('.gallery-items').append(response.html);
				
				unselectAll();
				selectItems();
			}
		});
	}
	
	return {
		'deleteItem': deleteItem,
		'getSelectedFilesId': getSelectedFilesId,
		'getSelectedItems': getSelectedItems,
		'initSelectedFiles': initSelectedFiles,
		'isSelected': isSelected,
		get multiple() {
			return multiple;
		},
		get pager() {
			return pager;
		}
	}
}
