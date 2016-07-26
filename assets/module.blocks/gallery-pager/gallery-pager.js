function GalleryPager(gallery) {
	var _gallery = gallery; // jQuery
	
	function countPages() {
		return _gallery.find('.pagination').eq(0).find('li:not(.prev, .next)').length;
	}
	
	function addPage() {
		var newPageNumber = countPages() + 1;
		
		var url = new Url();
		url.query.page = newPageNumber;
		url.protocol = '';
		url.user = '';
		url.pass = '';
		url.host = '';
		url.port = '';
		
		var newPageItem = $('<li/>').append(
			$('<a/>', {
				'href': url,
				'data-page': newPageNumber - 1,
				'text': newPageNumber
			})
		);
		
		var paginations = _gallery.find('.pagination');
		
		for (var i = 0; i < paginations.length; i++) {
			paginations.eq(i).find('li.next').before(newPageItem.clone());
		}
	}
	
	function deletePage() {
		var paginations = _gallery.find('.pagination');
		
		for (var i = 0; i < paginations.length; i++) {
			paginations.eq(i).find('li:not(.prev, .next)').last().remove();
		}
	}
	
	this.update = function(pagination) {
		console.log(pagination);
		
		if (countPages() < pagination.pages) {
			addPage();
		} else if (countPages() > pagination.pages) {
			deletePage();
		}
	}
}