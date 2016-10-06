/**
 * File gallery handler
 */
function FileGallery() {
	var _gallery;
	var _multiple;
	var _ajaxRequest = null;
	
	function init(gallery) {
		_gallery = gallery;
		_multiple = _gallery.data('multiple');
		
		return this;
	}
	
	function click(item) {
		toggleChecker(item);
		loadDetails(item);
	};
	
	function toggleChecker(item) {
		var checker = item.find('.file-gallery__checker');
		
		if (_multiple) {
			checker.toggleClass('file-gallery__checker_checked');
		} else {
			var sameItem = item.find('.file-gallery__checker').hasClass('file-gallery__checker_checked');
			
			uncheckAll();
			
			if (!sameItem) {
				checker.addClass('file-gallery__checker_checked');
			}
		}
	}
	
	function uncheckAll() {
		_gallery.find('.file-gallery__checker').removeClass('file-gallery__checker_checked');
	}
	
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
		
		if (item.find('.file-gallery__checker').hasClass('file-gallery__checker_checked')) {
			requestParams.data = "id=" + item.closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else if ($('.gallery-items__item .file-gallery__checker_checked').length > 0) {
			requestParams.data = "id=" + _gallery.find('.file-gallery__checker_checked').filter(':last')
				.closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else {
			$(_gallery.data('details-target')).html('');
		}
	}
	
	return {
		'init': init,
		'click': click,
		'uncheckAll': uncheckAll,
		'setAjaxLoader': setAjaxLoader
	}
}
