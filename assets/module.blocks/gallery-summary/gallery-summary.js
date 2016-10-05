function GallerySummary() {
	'use strict';
	
	var _gallery;
	var _pager;
	
	function init(gallery, pager) {
		_gallery = gallery;
		_pager = pager;
		
		return this;
	}
	
	function getRange() {
		return $(_gallery).find('.summary b').first().html().split('-');
	}
	
	function update(pagination) {
		_gallery.find('.summary').each(function(index, element) {
			$(element).find('b').last().html(pagination.files);
		});
		
		var filesRange = getRange();
		
		if (_pager.isLastPage()) {
			if (0 == filesRange[0] && 0 < pagination.files) {
				filesRange[0] = 1;
				filesRange[1] = pagination.files;
				_gallery.find('.summary b').first().html(filesRange.join('-'));
			} else if (0 == pagination.files) {
				filesRange[0] = 0;
				_gallery.find('.summary b').first().html('0');
			} else {
				filesRange[1] = pagination.files;
				_gallery.find('.summary b').first().html(filesRange.join('-'));
			}
		}
	}
	
	return {
		'init': init,
		'update': update
	}
}