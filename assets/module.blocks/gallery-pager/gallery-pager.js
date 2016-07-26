function GalleryPager(gallery) {
	'use strict';
	
	var _gallery = gallery; // jQuery
	
	function countPages() {
		return _gallery.find('.pagination').eq(0).find('li:not(.prev, .next)').length;
	}
	
	function addPage() {
		var newPageNumber = countPages() + 1;
		
		var url = new Url();
		url.query.page = newPageNumber;
		url.protocol = url.user = url.pass = url.host = url.port = '';
		
		var newPageItem = $('<li/>').append(
			$('<a/>', {
				'href': url,
				'data-page': newPageNumber - 1,
				'text': newPageNumber
			})
		);
		
		_gallery.find('.pagination').each(function(index, element) {
			$(element).find('li.next').before(newPageItem.clone());
		});
	}
	
	function deletePage() {
		_gallery.find('.pagination').each(function(index, element) {
			$(element).find('li:not(.prev, .next)').last().remove();
		});
	}
	
	this.update = function(pagination) {
		if (countPages() < pagination.pages) {
			addPage();
		} else if (countPages() > pagination.pages) {
			deletePage();
		}
	}
}