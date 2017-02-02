function GallerySummary() {
	'use strict';
	
	var _gallery;
	var _pager;
	
	function init(gallery, pager) {
		_gallery = gallery;
		_pager = pager;
		
		return this;
	}
	
	function update(pagination) {
		_gallery.find('.summary').each(function(index, element) {
			$(element).find('b').last().html(pagination.totalCount);
		});
		
		if (0 == pagination.totalCount) {
			_gallery.find('.summary b').first().html('0');
		} else {
			var filesRange = [pagination.begin, pagination.end];
			_gallery.find('.summary b').first().html(filesRange.join('-'));
		}
	}
	
	return {
		'init': init,
		'update': update
	}
}