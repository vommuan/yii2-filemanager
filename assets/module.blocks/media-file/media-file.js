/**
 * Gallery media file handler
 */
function MediaFile() {
	var _item;
	var _multiple;
	
	function init(item, multiple) {
		_item = item;
		_multiple = multiple;
		
		return this;
	}
	
	function click() {
		if (_multiple) {
			_item.toggleClass('media-file__link_checked');
		} else {
			var sameItem = _item.hasClass('media-file__link_checked');
			
			$('.media-file__link').removeClass('media-file__link_checked');
			
			if (!sameItem) {
				_item.addClass('media-file__link_checked');
			}
		}
		
		return this;
	};
	
	return {
		'init': init,
		'click': click
	}
}