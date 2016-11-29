/**
 * File gallery handler
 */
function FileGallery() {
	var _gallery;
	var _ajaxRequest = null;
	
	function init(gallery) {
		_gallery = gallery;
		
		return this;
	}
	
	function click(item) {
		loadDetails(item);
	};
	
	function setAjaxLoader() {
		if (undefined == _gallery) {
			console.log('Error. FileGallery: call init() before setAjaxLoader().');
			return;
		}
		
		$(_gallery.data('details-target')).html(
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
				$(_gallery.data('details-target')).html(html);
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
			$(_gallery.data('details-target')).html('');
		}
	}
	
	return {
		'init': init,
		'click': click,
		'setAjaxLoader': setAjaxLoader
	}
}
