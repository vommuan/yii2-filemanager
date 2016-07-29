function GallerySummary(gallery) {
	'use strict';
	
	var _gallery = gallery; // jQuery
	
	function getRange() {
		return $(_gallery).find('.summary b').first().html().split('-');
	}
	
	this.update = function(pagination) {
		$(_gallery).find('.summary').each(function(index, element) {
			$(element).find('b').last().html(pagination.files);
		});
		
		var filesRange = getRange();
		
		if ((new GalleryPager(_gallery)).isLastPage()) {
			filesRange[1] = pagination.files;
			$(_gallery).find('.summary b').first().html(filesRange.join('-'));
		}
	}
}