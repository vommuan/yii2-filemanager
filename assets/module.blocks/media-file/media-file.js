/**
 * Gallery media file handler
 */
function MediaFile() {
	var _gallery;
	var _multiple;
	
	function init(gallery) {
		_gallery = gallery;
		_multiple = _gallery.data('multiple');
		
		return this;
	}
	
	function click(item) {
		if (_multiple) {
			item.toggleClass('media-file__link_checked');
		} else {
			var sameItem = item.hasClass('media-file__link_checked');
			
			uncheckAll();
			
			if (!sameItem) {
				item.addClass('media-file__link_checked');
			}
		}
		
		return this;
	};
	
	function uncheckAll() {
		_gallery.find('.media-file__link').removeClass('media-file__link_checked');
	}
	
	return {
		'init': init,
		'click': click,
		'uncheckAll': uncheckAll
	}
}