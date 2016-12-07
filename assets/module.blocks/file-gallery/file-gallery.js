/**
 * File gallery handler
 */
function FileGallery() {
	var _gallery;
	var _fileDetails;
	var _multiple;
	var _ajaxRequest = null;
	
	function init(gallery) {
		_gallery = gallery;
		_fileDetails = _gallery.closest('.file-manager__content').find('.file-details');
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
		
		loadDetails(item);
		
		return this;
	};
	
	function uncheckAll() {
		_gallery.find('.media-file__link').removeClass('media-file__link_checked');
	}
	
	function setAjaxLoader() {
		if (undefined == _gallery) {
			console.log('Error. FileGallery: call init() before setAjaxLoader().');
			return;
		}
		
		_fileDetails.html(
			$('<div/>', {
				'class': 'loading'
			}).append(
				$('<span/>', {
					'class': 'glyphicon glyphicon-refresh spin'
				})
			)
		);
	}
	
	function loadDetails(item) {
		if (_ajaxRequest) {
			_ajaxRequest.abort();
			_ajaxRequest = null;
		}
		
		var requestParams = {
			type: "GET",
			url: _gallery.data("details-url"),
			beforeSend: setAjaxLoader,
			success: function(html) {
				_fileDetails.html(html);
			}
		};
		
		if (item.hasClass('media-file__link_checked')) {
			requestParams.data = "id=" + item.closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else if ($('.gallery-items__item .media-file__link_checked').length > 0) {
			requestParams.data = "id=" + _gallery.find('.media-file__link_checked').filter(':last')
				.closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else {
			_fileDetails.empty();
		}
	}
	
	return {
		'init': init,
		'click': click,
		'uncheckAll': uncheckAll,
		'setAjaxLoader': setAjaxLoader
	}
}
