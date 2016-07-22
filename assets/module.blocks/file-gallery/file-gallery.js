/**
 * File gallery handler
 */
function FileGallery(item) {
	var _item = item;
	var _ajaxRequest = null;
	
	this.itemClick = function() {
		toggleChecker();
		loadDetails();
	};
	
	function toggleChecker() {
		var checker = $(_item).find('.file-gallery__checker');
		
		if (0 == checker.length) {
			checker = $(_item).closest('.file-gallery').find('.file-gallery__checker:last').clone().appendTo(_item);
		}
		
		if ($(_item).closest('.file-gallery').data('multiple')) {
			checker.toggleClass('file-gallery__checker_checked');
		} else {
			$('.gallery-items__item .file-gallery__checker_checked').removeClass('file-gallery__checker_checked');
			checker.addClass('file-gallery__checker_checked');
		}
	}
	
	function setAjaxLoader() {
		$("#fileinfo").html('<div class="loading"><span class="glyphicon glyphicon-refresh spin"></span></div>');
	}
	
	function loadDetails() {
		if (_ajaxRequest) {
			_ajaxRequest.abort();
			_ajaxRequest = null;
		}

		_ajaxRequest = $.ajax({
			type: "GET",
			url: $(_item).closest('.file-gallery').data("details-url"),
			data: "id=" + $(_item).closest('.gallery-items__item').data("key"),
			beforeSend: setAjaxLoader,
			success: function(html) {
				$("#fileinfo").html(html);
			}
		});
	}
}

$('.media-file__link').on("click", function(event) {
	event.preventDefault();
	
	var fileGallery = new FileGallery(this);
	
	fileGallery.itemClick();
});