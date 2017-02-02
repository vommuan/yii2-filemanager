function GalleryPager() {
	'use strict';
	
	var _pagination;
	var _pageSelector = 'li:not(.first, .prev, .next, .last)';
	var _firstPageLinkOptions;
	var _prevPageLinkOptions;
	var _nextPageLinkOptions;
	var _lastPageLinkOptions;
	
	function getPageLink(page) {
		var url = new Url(_pagination.find('a').eq(0).attr('href'), true);
		
		url.query.page = page;
		
		return url.toString();
	}
	
	function initFirstPage() {
		_firstPageLinkOptions = {
			'href': getPageLink(1),
			'data-page': 0,
			'html': $.trim(_pagination.find('.first').eq(0).text())
		};
	}
	
	function initPreviousPage() {
		var datePage = getPreviousPage();
		
		if (datePage < 0) {
			datePage = 0;
		}
		
		_prevPageLinkOptions = {
			'href': getPageLink(datePage),
			'data-page': datePage - 1, // one-based to zero-based
			'html': $.trim(_pagination.find('.prev').eq(0).text())
		};
	}
	
	function initNextPage() {
		var datePage = getNextPage(); // one-based to zero-based
		
		if (datePage > countPages()) {
			datePage = countPages(); 
		}
		
		_nextPageLinkOptions = {
			'href': getPageLink(datePage),
			'data-page': datePage - 1, // zero-based last page number
			'html': $.trim(_pagination.find('.next').eq(0).text())
		};
	}
	
	function initLastPage() {
		_lastPageLinkOptions = {
			'href': getPageLink(countPages()),
			'data-page': countPages() - 1, // zero-based last page number
			'html': $.trim(_pagination.find('.last').eq(0).text())
		};
	}
	
	function init(gallery) {
		_pagination = gallery.find('.pagination');
		
		initFirstPage();
		initPreviousPage();
		initNextPage();
		initLastPage();
		
		return this;
	}
	
	function isControlItem(link) {
		var item = link.closest('li');
		
		if (item.hasClass('first') || item.hasClass('prev') ||  item.hasClass('next') || item.hasClass('last')) {
			return true;
		} else {
			return false;
		}
	}
	
	function addLink(pagerItem, options) {
		var text = pagerItem.find('span').html();
		
		pagerItem.find('span').remove();
		pagerItem.html($('<a/>', options));
	}
	
	function removeLink(pagerItem) {
		var text = pagerItem.find('a').html();
		
		pagerItem.find('a').remove();
		pagerItem.html($('<span/>', {'html': text}));
	}
	
	function click(link) {
		if (isControlItem(link)) {
			link = _pagination.find(_pageSelector + ' a[data-page="' + link.data('page') + '"]').eq(0);
		}
		
		_pagination.find('.active').removeClass('active');
		_pagination.find(_pageSelector + ' a[data-page="' + link.data('page') + '"]').closest('li').addClass('active');
		
		initPreviousPage();
		initNextPage();
		
		if (isFirstPage()) {
			if (!_pagination.find('.first, .prev').hasClass('disabled')) {
				_pagination.find('.first, .prev').addClass('disabled');
				removeLink(_pagination.find('.first'));
				removeLink(_pagination.find('.prev'));
			}
		} else {
			if (_pagination.find('.first, .prev').hasClass('disabled')) {
				_pagination.find('.first, .prev').removeClass('disabled');
				addLink(_pagination.find('.first'), _firstPageLinkOptions);
				addLink(_pagination.find('.prev'), _prevPageLinkOptions);
			} else {
				removeLink(_pagination.find('.prev'));
				addLink(_pagination.find('.prev'), _prevPageLinkOptions);
			}
		}
		
		if (isLastPage()) {
			if (!_pagination.find('.next, .last').hasClass('disabled')) {
				_pagination.find('.next, .last').addClass('disabled');
				removeLink(_pagination.find('.next'));
				removeLink(_pagination.find('_pagination.last'));
			}
		} else {
			if (_pagination.find('.next, .last').hasClass('disabled')) {
				_pagination.find('.next, .last').removeClass('disabled');
				addLink(_pagination.find('.next'), _nextPageLinkOptions);
				addLink(_pagination.find('.last'), _lastPageLinkOptions);
			} else {
				removeLink(_pagination.find('_pagination.next'));
				addLink(_pagination.find('.next'), _nextPageLinkOptions);
			}
		}
	}
	
	function countPages() {
		return _pagination.eq(0).find(_pageSelector).length;
	}
	
	function addPage() {
		var newPageItem = $('<li/>').append(
			$('<a/>', {
				'href': getPageLink(getNextPage()),
				'data-page': getNextPage() - 1,
				'text': getNextPage()
			})
		);
		
		_pagination.each(function(index, element) {
			$(element).find('li.next').before(newPageItem.clone());
		});
		
		initLastPage();
		
		var dataPage = getCurrentPage() - 1;
		
		click(_pagination.find(_pageSelector + ' a[data-page="' + dataPage + '"]').eq(0));
	}
	
	function deletePage() {
		var reload = isLastPage() && countPages() > 1;
		
		if (reload) {
			var href = getPageLink(getPreviousPage());
			var dataPage = getPreviousPage() - 1;
		} else {
			var dataPage = getCurrentPage() - 1;
		}
		
		if (countPages() > 1) {
			_pagination.each(function(index, element) {
				$(element).find(_pageSelector).last().remove();
			});
		}
		
		if (reload) {
			var link = _pagination.find(_pageSelector + ' a[data-page="' + dataPage + '"]').eq(0);
			
			link.trigger('click');
		}
	}
	
	function update(pagination) {
		if (countPages() < pagination.pageCount) {
			addPage();
		} else if (pagination.pageCount < countPages()) {
			deletePage();
		}
	}
	
	function getPreviousPage() {
		return getCurrentPage() - 1;
	}
	
	function getCurrentPage() {
		return parseInt($.trim(_pagination.find('.active').eq(0).text()));
	}
	
	function getNextPage() {
		return getCurrentPage() + 1;
	}
	
	function isFirstPage() {
		return 1 == getCurrentPage();
	}
	
	function isLastPage() {
		return getCurrentPage() == countPages();
	}
	
	return {
		'init': init,
		'click': click, 
		'update': update,
		'getCurrentPage': getCurrentPage,
		'isLastPage': isLastPage
	}
}