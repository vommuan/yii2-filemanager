function GalleryPager(gallery) {
	'use strict';
	
	var _gallery = gallery; // jQuery
	
	function countPages() {
		return _gallery.find('.pagination').eq(0).find('li:not(.first, .prev, .next, .last)').length;
	}
	
	function addPage() {
		var newPageNumber = countPages() + 1;
		
		var url = new Url(); // library bower/domurl
		url.query.page = newPageNumber;
		url.protocol = url.user = url.pass = url.host = url.port = ''; // make relative url
		
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
		var reload = isLastPage();
		
		if (reload) {
			var url = new Url();
			url.query.page = getCurrentPage() - 1;
			
			if (0 == url.query.page) {
				reload = false;
			}
		}
		
		if (countPages() > 1) {
			_gallery.find('.pagination').each(function(index, element) {
				$(element).find('li:not(.first, .prev, .next, .last)').last().remove();
			});
		}
		
		if (reload) {
			window.location = url;
		}
	}
	
	this.update = function(pagination) {
		if (countPages() == pagination.pages) {
			return true;
		}
		
		if (countPages() < pagination.pages) {
			addPage();
		} else if (countPages() > pagination.pages) {
			deletePage();
		}
		
		return true;
	}
	
	function getCurrentPage() {
		var pagination = _gallery.find('.pagination').eq(0);
		
		return pagination.find('li:not(.first, .prev, .next, .last)').index(pagination.find('li.active')) + 1;
	}
	
	function isLastPage() {
		return getCurrentPage() == countPages();
	}
	
	this.getCurrentPage = getCurrentPage;
	this.isLastPage = isLastPage;
}