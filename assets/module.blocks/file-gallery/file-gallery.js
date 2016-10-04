/**
 * File gallery handler
 */
function FileGallery(item) {
	var _item = item;
	var _ajaxRequest = null;
	
	function itemClick() {
		toggleChecker();
		loadDetails();
	};
	
	function toggleChecker() {
		var checker = $(_item).find('.file-gallery__checker');
		
		if (0 == checker.length) {
			checker = $(_item).closest('.file-gallery').find('.file-gallery__checker:last').clone();
			checker.appendTo(_item);
		}
		
		if ($(_item).closest('.file-gallery').data('multiple')) {
			checker.toggleClass('file-gallery__checker_checked');
		} else {
			var sameItem = $(_item).find('.file-gallery__checker').hasClass('file-gallery__checker_checked');
			
			$('.gallery-items__item .file-gallery__checker').removeClass('file-gallery__checker_checked');
			
			if (!sameItem) {
				checker.addClass('file-gallery__checker_checked');
			}
		}
	}
	
	function setAjaxLoader() {
		$($(_item).closest('.file-gallery').data('details-target')).html(
			$('<div/>', {
				'class': 'loading'
			}).append(
				$('<span/>', {
					'class': 'glyphicon glyphicon-refresh spin'
				})
			)
		);
	}
	
	function loadDetails() {
		if (_ajaxRequest) {
			_ajaxRequest.abort();
			_ajaxRequest = null;
		}
		
		var requestParams = {
			type: "GET",
			url: $(_item).closest('.file-gallery').data("details-url"),
			beforeSend: setAjaxLoader,
			success: function(html) {
				$($(_item).closest('.file-gallery').data('details-target')).html(html);
			}
		};
		
		if ($(_item).find('.file-gallery__checker').hasClass('file-gallery__checker_checked')) {
			requestParams.data = "id=" + $(_item).closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else if ($('.gallery-items__item .file-gallery__checker_checked').length > 0) {
			requestParams.data = "id=" + $(_item).closest('.file-gallery')
				.find('.gallery-items__item .file-gallery__checker_checked').filter(':last')
				.closest('.gallery-items__item').data("key");
			
			_ajaxRequest = $.ajax(requestParams);
		} else {
			$($(_item).closest('.file-gallery').data('details-target')).html('');
		}
	}
	
	return {
		'itemClick': itemClick,
		'setAjaxLoader': setAjaxLoader
	}
}
